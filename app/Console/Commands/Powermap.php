<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class Summary extends Command
{
    // the range at which a Fortified system can support others
    const LINK_RANGE = 20;
    // the year offset
    const YEAR_OFFSET = (3300-2014);
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heatmap:powermap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make power maps';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $powers = ["Example" => "Lave"];
        foreach ($powers as $power => $hq) {
            $this->info("Generating ".$power." from ".$hq);
            $this->makeGraphViz($power, $hq);
        }
    }

    public function distance (System $a, System $b) {
        return sqrt(
            (($a->x - $b->x) * ($a->x - $b->x)) +
            (($a->y - $b->y) * ($a->y - $b->y)) +
            (($a->z - $b->z) * ($a->z - $b->z))
        );
    }

    public function week(Carbon $date) {
        return max(0, floor($date->diffInWeeks(Carbon::parse("2024-10-24"))));
    }
    
    public function makeGraphViz($power, $hqname) {
        $hq = System::where('name', $hqname)->first();
        $controls = System::where('power', $power)->where('name', '!=', $hqname)->whereIn('powerstate', ['Fortified', 'Stronghold'])->get()->sort(function($a, $b) use ($hq) {
            return $this->distance($a, $hq) - $this->distance($b, $hq);
        });
        $now = Carbon::now();
        $weekno = $this->week($now);

        $links = [];
        $linked = [$hq];
        do {
            $added = [];
            
            foreach ($controls as $cidx => $control) {
                foreach ($linked as $node) {
                    if ($this->distance($node, $control) <= self::LINK_RANGE) {
                        $added[$cidx] = $control;
                        $links[] = [$node, $control];
                    }
                }
            }
            if (count($added) == 0) {
                // no additions, so use the nearest disconnected as
                // the base for a new tree
                foreach ($controls as $cidx => $control) {
                    $added[$cidx] = $control;
                    break;
                }
            }
            krsort($added);
            foreach ($added as $idx => $system) {
                $linked[] = $system;
                $controls->forget($idx);
            }

        } while ($controls->count() > 0);

        $output = "digraph PowerChart {\n";
        $output .= "node [fontsize=9;fontname=sansserif;]\n";
        $output .= "labelloc=t;label =\"".$power.", week ".$weekno." (".$now->format("j F")." ".($now->format("Y")+self::YEAR_OFFSET).")\"\n";
        
        foreach ($linked as $system) {
            // define system nodes
            if ($system->name == $hqname) {
                $output .= '"'.$system->name.'" [shape=star; penwidth=4];'."\n";
            } elseif ($system->powerstate == "Fortified") {
                $output .= '"'.$system->name.'" [shape=rectangle];'."\n";
            } else {
                $output .= '"'.$system->name.'" [shape=rectangle; penwidth=4];'."\n";
            }
        }
        foreach ($links as $link) {
            // define system connectors
            $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'";'."\n";
        }

        $output .= "}";
        file_put_contents("/tmp/graph.gv", $output);
        system("dot -Tpng /tmp/graph.gv > /tmp/graph.png");

$powerfile = preg_replace("/[^A-Za-z-]/", "", $power);
        
               \Storage::disk('public')->put($weekno."/".$powerfile.".png", fopen("/tmp/graph.png", "r"));
    }
    
}
