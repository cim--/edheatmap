<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class Powerconnect extends Command
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
    protected $signature = 'heatmap:powerconnect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make power connectivity maps';

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
        /* Temp import */
        // $this->importSystems();
        
        $powers = [
            "Aisling Duval" => "Cubeo",
            "Archon Delaine" => "Harma",
            "A. Lavigny-Duval" => "Kamadhenu",
            "Denton Patreus" => "Eotienses",
            "Edmund Mahon" => "Gateway",
            "Felicia Winters" => "Rhea",
            "Jerome Archer" => "Nanomam",
            //"Zachary Hudson" => "Nanomam",
            "Li Yong-Rui" => "Lembava",
            "Nakato Kaine" => "Tionisla",
            "Pranav Antal" => "Polevnic",
            "Yuri Grom" => "Clayakarma",
            "Zemina Torval" => "Synteini"
        ];
        foreach ($powers as $power => $hq) {
            $this->info("Generating ".$power." from ".$hq);
            $this->makeGraphViz($power, $hq);
        }
    }

    public function importSystems()
    {
        $systems = file("/tmp/systems.tab");
        $c = count($systems);
        foreach ($systems as $idx => $sysline) {
            $data = explode("\t", trim($sysline));
            $this->line("Processing ".$idx."/".$c.": ".$data[0]);
            $system = System::where('name', $data[0])->firstOrNew();
            $system->name = $data[0];
            $system->x = $data[1];
            $system->y = $data[2];
            $system->z = $data[3];
            $system->power = $data[4];
            $system->powerstate = ($data[5] == "Exploited" ? "Exploited" : "Fortified");
            $system->save();
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
        return (int)max(0, floor($date->diffInWeeks(Carbon::parse("2024-10-24"),true)));
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
        $hardlinks = [];
        $softlinks = [];
        $conset = 0;
        $controls = collect([$hq])->concat($controls);
        /* Prepare graph of Fortified/Stronghold systems */

        foreach ($controls as $cidx => $control) {
            foreach ($controls as $cidx2 => $control2) {
                if ($cidx2 <= $cidx) { continue; }
                if ($control->powerstate == "Stronghold" || $control2->powerstate == "Stronghold" || $control->name == $hqname) {
                    $range = self::LINK_RANGE_STRONGHOLD;
                } else {
                    $range = self::LINK_RANGE_FORTIFIED;
                }

                if ($this->distance($control2, $control) <= $range) {
                    $links[] = [$control, $control2];
                    $hardlinks[$control->id][$control2->id] = 1;
                    $hardlinks[$control2->id][$control->id] = 1;
                } 
            }
        }

        /* Prepare assessment of Exploited system connectivity */
        $celinks = [];
        $eclinks = [];
        $singletons = [];
        foreach ($exploited as $esys) {
            $connections = 0;
            foreach ($controls as $csys) {
                if ($csys->powerstate == "Stronghold" || $csys->name == $hqname) {
                    $range = self::LINK_RANGE_STRONGHOLD;
                } else {
                    $range = self::LINK_RANGE_FORTIFIED;
                }
                if ($this->distance($esys, $csys) <= $range) {
                    $celinks[$csys->name][$esys->name] = 1;
                    $eclinks[$esys->name][] = $csys;
                    $connections++;
                }
            }
            if ($connections == 1) {
                $singletons[$esys->name] = 1;
            }
        }

        // set up shared exploit softlinks
        foreach ($eclinks as $esys => $supporters) {
            for ($i=0;$i<count($supporters)-1;$i++) {
                for ($j=$i+1;$j<count($supporters);$j++) {
                    if(!isset($hardlinks[$supporters[$i]->id][$supporters[$j]->id])) {
                        $softlinks[$supporters[$i]->id."_".$supporters[$j]->id] = [$supporters[$i], $supporters[$j]];
                    }
                }
            }
        }
        
        
        $output = "digraph PowerChart {\n";
        $output .= "node [fontsize=9;fontname=sansserif;]\n";
        $output .= "labelloc=t;label =\"".$power.", week ".$weekno." (generated ".$now->format("j F")." ".($now->format("Y")+self::YEAR_OFFSET).")\"\n";
        //        $output .= "layout=dot; ranksep=1;\n";
        $output .= "layout=neato;overlap=prism;outputorder=edgesfirst; splines=true;\n";
        // $output .= "layout=twopi;overlap=prism;outputorder=edgesfirst; splines=true;\n";

        $maxvirt = 0;
        foreach ($controls as $system) {
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
            $fillcolor = "white";
            if ($scount >= 20) {
                $fillcolor = "#ff4444";
            } else if ($scount >= 10) {
                $fillcolor = "#ff99ff";
            } else if ($scount >= 5) {
                $fillcolor = "#bbbbff";
            } else if ($scount > 0) {
                $fillcolor = "#ddffff";
            }
            
            
            if ($system->name == $hqname) {
                $output .= '"'.$system->name.'" [shape=star; penwidth=4;'.$nodelabel.'];'."\n";
            } elseif ($system->powerstate == "Fortified") {
                $output .= '"'.$system->name.'" [shape=rectangle;style=filled;fillcolor="'.$fillcolor.'";'.$nodelabel.'];'."\n";
            } else {
                $output .= '"'.$system->name.'" [shape=rectangle;style=filled;fillcolor="'.$fillcolor.'"; penwidth=4;'.$nodelabel.'];'."\n";
            }
        }

        foreach ($softlinks as $softlink) {
            // show shared exploited systems
            // (draw these first)
            $output .= '"'.$softlink[0]->name.'" -> "'.$softlink[1]->name.'" [color="#bbbbbb";dir=none;constraint=false];'."\n";
        }
        
        foreach ($links as $link) {
            // define system connectors
            if ($this->distance($link[0], $link[1]) > self::LINK_RANGE_FORTIFIED && $link[0]->name != $hqname && $link[1]->name != $hqname) {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'" [style=dashed];'."\n";
            } else {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'";'."\n";
            }
        }
                

        $output .= 'mapkey [shape=rect;style=filled;label="KEY\nStar: HQ\lThick edge rectangle: Stronghold\lNormal edge rectangle: Fortified\lBelow system name: supported exploited count (sole supporter count)\lArrows point away from the HQ but position is not otherwise meaningful\lDashed lines indicate links requiring current Stronghold status\lLight grey lines show systems which share exploited systems\lWhite->Cyan->Blue->Pink->Red colour indicates sole-supported exploited systems\l"];'."\n";
        
        $output .= "}";
        file_put_contents("/tmp/graph.gv", $output);
        system("dot -Tpng /tmp/graph.gv > /tmp/graph.png");

        $powerfile = preg_replace("/[^A-Za-z-]/", "", $power);
        
               \Storage::disk('public')->put($weekno."/".$powerfile.".connections.png", fopen("/tmp/graph.png", "r"));
    }
    
}
