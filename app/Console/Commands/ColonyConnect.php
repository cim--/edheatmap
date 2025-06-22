<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class ColonyConnect extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heatmap:colonyconnect';

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
    
        $colonies = System::where('created_at', '>', '2025-02-01')->orderBy('z')->get();
        $c = $colonies->count();
        $this->info("Checking ".$c." colonies");
        for ($i=0; $i<$c; $i++) {
            $colonies[$i]->connections = 0;
            $colonies[$i]->connected = [];
        }
        
        for ($i=0; $i<$c; $i++) {
            if ($i%1000 == 0 && $i>0) {
                $this->line("Checked ".$i);
            }
            for ($j=$i+1; $j<$c; $j++) {
                if ($colonies[$i]->distance($colonies[$j]) <= 15) {
                    $colonies[$i]->connections++;
                    $colonies[$j]->connections++;
                    if ($colonies[$i]->connections <= 2) {
                        $colonies[$i]->connected = array_merge($colonies[$i]->connected, [$colonies[$j]]);
                    }
                    if ($colonies[$j]->connections <= 2) {
                        $colonies[$j]->connected = array_merge($colonies[$j]->connected, [$colonies[$i]]);
                    }
                }
                if ($colonies[$j]->z - $colonies[$i]->z > 15) {
                    // quick optimisation
                    break;
                }
            }
        }
        $this->info("Super-connected");
        $connections = [];
        for ($i=0; $i<$c; $i++) {
            if (!isset($colonies[$i]->connections)) {
                $colonies[$i]->connections = 0;
            }
            $conn = $colonies[$i]->connections;
            if (!isset($connections[$conn])) {
                $connections[$conn] = 0;
            }
            if ($conn > 50) {
                $this->line($colonies[$i]->name.": ".$colonies[$i]->connections);
            }
            $connections[$conn]++;
        }

        ksort($connections);
        $this->info("Results");
        foreach ($connections as $ct => $num) {
            $this->line($ct.": ".$num);
        }

        
        

        $sol = System::where('name', 'Sol')->first();
        $dists = [];
        $pops = [];
        $totalchain = 0;
        $chains = [];
        for ($i=0; $i<$c; $i++) {
            if ($colonies[$i]->connections == 2) {
                /*$dist = $sol->distance($colonies[$i]);                

                $t = $colonies[$i];
                $a = $colonies[$i]->connected[0];
                $b = $colonies[$i]->connected[1];
                $dot = ((($t->x - $a->x) * ($t->x - $b->x)) +
                     (($t->y - $a->y) * ($t->y - $b->y)) +
                     (($t->z - $a->z) * ($t->z - $b->z))) /
                     ($t->distance($a) * $t->distance($b));
                if ($dot > 0) {
                    // angle < 90 degrees
                    // unlikely to be a chain
                    continue;
                    }*/
                
                /* if (($dist-$sol->distance($colonies[$i]->connected[0])) * ($dist-$sol->distance($colonies[$i]->connected[1])) > 0) {
                    // if this is positive or zero, then both
                    // connections are on the same side as Sol, or
                    // both opposite it, so this is more likely to be
                    // an edge system of a group than a chain between
                    // the connections
                    continue;
                    } */
                // candidate for being part of a chain
                $chains[$colonies[$i]->name] = $colonies[$i];
            }
        }
        $linkedchains = [];
        foreach ($chains as $chain) {
            $a = $chain->connected[0];
            $b = $chain->connected[1];
                            
            if (isset($chains[$a->name]) && isset($chains[$b->name])) {
                $totalchain++;
                $linkedchains[] = $chain;
                
                $dist = $sol->distance($chain);    
                $d10 = 10*floor($dist/10);
                if (!isset($dists[$d10])) {
                    $dists[$d10] = 0;
                }
                $dists[$d10]++;
                if ($chain->population > 0) {
                    $pop = 10 ** floor(log($chain->population, 10));
                    if (!isset($pops[$pop])) {
                        $pops[$pop] = 0;
                    }
                    $pops[$pop]++;
                }
            }
        }

        
        
        ksort($dists);
        ksort($pops);

        $this->line("Total chains: ".$totalchain);
        $this->line("Linked chains: ".count($linkedchains));
        
        $this->info("Distances of chaining systems");
        foreach ($dists as $dist => $count) {
            $this->line($dist." - ".($dist+10).": ".$count);
        }
        $this->info("Populations of chaining systems");
        foreach ($pops as $pop => $count) {
            $this->line($pop." - ".$pop."0: ".$count);
        }

        $this->info("Chain system names and distances");
        foreach ($linkedchains as $chain) {
            $this->line($chain->name." = ".$sol->distance($chain));
        }
    }
    
}
