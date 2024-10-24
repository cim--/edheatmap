<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class Summary extends Command
{
    // the range at which a Fortified system can support others
    const LINK_RANGE_FORTIFIED = 20;
    const LINK_RANGE_STRONGHOLD = 30;
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
        $exploited = System::where('power', $power)->where('name', '!=', $hqname)->whereIn('powerstate', ['Exploited'])->get();
        $now = Carbon::now();
        $weekno = $this->week($now);

        $links = [];
        $virtlinks = [];
        $softlinks = [];
        $conset = 0;
        $linked = [$hq];
        /* Prepare graph of Fortified/Stronghold systems */
        do {
            $added = [];
            
            foreach ($controls as $cidx => $control) {
                foreach ($linked as $node) {
                    if ($node->powerstate == "Stronghold" || $node->name == $hqname) {
                        $range = self::LINK_RANGE_STRONGHOLD;
                    } else {
                        $range = self::LINK_RANGE_FORTIFIED;
                    }
                    if ($this->distance($node, $control) <= $range) {
                        $added[$cidx] = $control;
                        $links[] = [$node, $control];
                    } elseif ($this->distance($node, $control) <= self::LINK_RANGE_STRONGHOLD) {
                        $softlinks[$conset][$node->id."_".$control->id] = [$node, $control];
                    }
                }
            }
            if (count($added) == 0) {
                // remove any softlinks in the current conset between already-connected systems
                if (isset($softlinks[$conset])) {
                    foreach ($softlinks[$conset] as $linkid => $linkdata) {
                        list($srcid, $destid) = explode("_", $linkid);
                        if ($controls->where('id', $destid)->count() == 0) {
                            // it's no longer in the set, so it's been
                            // connected some other way
                            unset($softlinks[$conset][$linkid]);
                        }
                        // also ignore softlinks already in a previous conset
                        for ($i=0;$i<$conset;$i++) {
                            if (isset($softlinks[$i][$linkid])) {
                                unset($softlinks[$conset][$linkid]);
                            }
                        }
                    }
                }
                
                // no additions, so use the nearest disconnected as
                // the base for a new tree
                foreach ($controls as $cidx => $control) {
                    $added[$cidx] = $control;
                    // link it to the right hierarchy level
                    $band = floor($this->distance($control, $hq) / self::LINK_RANGE_FORTIFIED);
                    $virtlinks[] = [$band, $control];
                    break;
                }
                $conset++;
            }
            krsort($added);
            foreach ($added as $idx => $system) {
                $linked[] = $system;
                $controls->forget($idx);
            }

        } while ($controls->count() > 0);

        /* Prepare assessment of Exploited system connectivity */
        $celinks = [];
        $singletons = [];
        foreach ($exploited as $esys) {
            $connections = 0;
            foreach ($linked as $csys) {
                if ($csys->powerstate == "Stronghold" || $csys->name == $hqname) {
                    $range = self::LINK_RANGE_STRONGHOLD;
                } else {
                    $range = self::LINK_RANGE_FORTIFIED;
                }
                if ($this->distance($esys, $csys) <= $range) {
                    $celinks[$csys->name][$esys->name] = 1;
                    $connections++;
                }
            }
            if ($connections == 1) {
                $singletons[$esys->name] = 1;
            }
        }
        
        
        $output = "digraph PowerChart {\n";
        $output .= "node [fontsize=9;fontname=sansserif;]\n";
        $output .= "labelloc=t;label =\"".$power.", week ".$weekno." (generated ".$now->format("j F")." ".($now->format("Y")+self::YEAR_OFFSET).")\"\n";
        $output .= "layout=dot; ranksep=1;\n";
        
        foreach ($linked as $system) {
            // define system nodes
            if (isset($celinks[$system->name])) {
                $cecount = count($celinks[$system->name]);
                $scount = 0;
                foreach ($celinks[$system->name] as $esys => $discard) {
                    if (isset($singletons[$esys])) {
                        $scount++; 
                    }
                }
            } else {
                $cecount = 0;
                $scount = 0;
            }
            $nodelabel = 'label="'.$system->name.'\n'.$cecount." (".$scount.')"';
            if ($system->name == $hqname) {
                $output .= '"'.$system->name.'" [shape=star; penwidth=4;'.$nodelabel.'];'."\n";
            } elseif ($system->powerstate == "Fortified") {
                $output .= '"'.$system->name.'" [shape=rectangle;'.$nodelabel.'];'."\n";
            } else {
                $output .= '"'.$system->name.'" [shape=rectangle; penwidth=4;'.$nodelabel.'];'."\n";
            }
        }
        foreach ($links as $link) {
            // define system connectors
            if ($this->distance($link[0], $link[1]) > self::LINK_RANGE_FORTIFIED) {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'" [style=dashed];'."\n";
            } else {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'";'."\n";
            }
        }
        foreach ($softlinks as $softgroup) {
            foreach ($softgroup as $softlink) {
                // define system connectors that could exist between strongholds
                $output .= '"'.$softlink[0]->name.'" -> "'.$softlink[1]->name.'" [style=dotted;constraint=false];'."\n";
            }
        }
        $maxvirt = 0;
        foreach ($virtlinks as $virtlink) {
            $output .= '"Virtual '.$virtlink[0].'" -> "'.$virtlink[1]->name.'" [style=invis];'."\n";
            $maxvirt = max($maxvirt, $virtlink[0]);
        }
        for ($i = 1; $i <= $maxvirt; $i++) {
            if ($i != 1) {
                $output .= '"Virtual '.($i-1).'" -> "Virtual '.$i.'" [style=invis];'."\n";
            } else {
                $output .= '"'.$hqname.'" -> "Virtual '.$i.'" [style=invis];'."\n";
            }
            $output .= '"Virtual '.$i.'" [style=invis];'."\n";
        }

        $output .= 'mapkey [shape=rect;style=filled;label="KEY\nStar: HQ\lThick edge rectangle: Stronghold\lNormal edge rectangle: Fortified\lBelow system name: supported exploited count (sole supporter count)\lShortest connections back to HQ only\lExclaves shown at approximate distance\lDotted lines indicate links which could be created to exclaves by reinforcement to Stronghold\lDashed lines indicate links requiring current Stronghold status\l"];'."\n";
        
        $output .= "}";
        file_put_contents("/tmp/graph.gv", $output);
        system("dot -Tpng /tmp/graph.gv > /tmp/graph.png");

$powerfile = preg_replace("/[^A-Za-z-]/", "", $power);
        
               \Storage::disk('public')->put($weekno."/".$powerfile.".png", fopen("/tmp/graph.png", "r"));
    }
    
}
