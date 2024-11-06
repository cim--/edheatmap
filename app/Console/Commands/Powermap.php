<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class Powermap extends Command
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
    protected $description = 'Make power hierarchy maps';

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
        return \App\Util::week($date);
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
                    if ($node->powerstate == "Stronghold" || $node->name == $hqname || $control->powerstate == "Stronghold") {
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
        //        $output .= "layout=dot; ranksep=1;\n";
        // $output .= "layout=twopi;overlap=prism;outputorder=edgesfirst; splines=true;\n";

        $maxvirt = 0;
        foreach ($virtlinks as $virtlink) {
            $output .= '"Virtual '.$virtlink[0].'" -> "'.$virtlink[1]->name.'" [style=invis];'."\n";
            $maxvirt = max($maxvirt, $virtlink[0]);
        }
        for ($i = 1; $i <= $maxvirt; $i++) {
            if ($i != 1) {
                $output .= '"Virtual '.($i-1).'" -> "Virtual '.$i.'" [style=invis;weight=4];'."\n";
            } else {
                $output .= '"'.$hqname.'" -> "Virtual '.$i.'" [style=invis;weight=4];'."\n";
            }
            $output .= '"Virtual '.$i.'" [style=invis];'."\n";
        }
        
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
        foreach ($links as $link) {
            // define system connectors
            if ($this->distance($link[0], $link[1]) > self::LINK_RANGE_FORTIFIED && $link[0]->name != $hqname && $link[1]->name != $hqname) {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'" [style=dashed];'."\n";
            } else {
                $output .= '"'.$link[0]->name.'" -> "'.$link[1]->name.'";'."\n";
            }
        }
        foreach ($softlinks as $softgroup) {
            foreach ($softgroup as $softlink) {
                // define system connectors that could exist between strongholds
                // not on the normal graph, too messy
                // $output .= '"'.$softlink[0]->name.'" -> "'.$softlink[1]->name.'" [style=dotted;constraint=false];'."\n";
            }
        }
        

        $output .= 'mapkey [shape=rect;style=filled;label="KEY\nStar: HQ\lThick edge rectangle: Stronghold\lNormal edge rectangle: Fortified\lBelow system name: supported exploited count (sole supporter count)\lOnly shortest connected routes back to HQ shown\lExclaves shown at approximate distance\lDashed lines indicate links requiring current Stronghold status\lWhite->Cyan->Blue->Pink->Red colour indicates sole-supported exploited systems\l"];'."\n";
        
        $output .= "}";
        file_put_contents("/tmp/graph.gv", $output);
        system("dot -Tpng /tmp/graph.gv > /tmp/graph.png");

        $powerfile = preg_replace("/[^A-Za-z-]/", "", $power);
        
        \Storage::disk('public')->put($weekno."/".$powerfile.".hierarchy.png", fopen("/tmp/graph.png", "r"));
    }
    
}
