<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\System;
use Carbon\Carbon;

class ColonyConnect2 extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heatmap:colonyconnect2';

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

    private $sol;
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	$sol = System::where('name', 'Sol')->where('population', '>', 0)->first();
	$this->sol = $sol;
        $systems = System::orderBy('z')->get();
	$this->info("Found ".$systems->count()." systems");
	$boxes = [];
	// preprocess for more efficient distance searches
	$totalcolonies = 0;
	foreach ($systems as $system) {
	    if ($this->boxUp($boxes, $system)) {
		$totalcolonies++;
	    }
	}
	$this->info("Prepared boxes");
	$this->info("Considering ".$totalcolonies." systems");

	$connectionlevels = [];
	$distlevels = [];
	$poplevels = [];

	$closest1 = 5000;
	$closest1sys = null;
	
	foreach ($boxes as $x => $xbox) {
	    //	    $this->line("Processing x box ".$x);
	    foreach ($xbox as $y => $ybox) {
		foreach ($ybox as $z => $zbox) {
		    foreach ($zbox['in'] as $system) {
			$dist = $system->distance($sol);
			if (!isset($distlevels[ceil($dist/50)*50])) {
			    $distlevels[ceil($dist/50)*50] = 0;
			}
			$distlevels[ceil($dist/50)*50]++;
			
			$pop = 10**ceil(log($system->population, 10));
			if (!isset($poplevels[$pop])) {
			    $poplevels[$pop] = 0;
			}
			$poplevels[$pop]++;
			
			$near = $this->countNear($system, $zbox['near']);
			if (!isset($connectionlevels[$near])) {
			    $connectionlevels[$near] = 0;
			}
			if ($near <= 1) {
			    if ($closest1 > $dist) {
				$closest1 = $dist;
				$closest1sys = $system;
			    }
			}
			$connectionlevels[$near]++;
		    }
		}
	    }
	}
	ksort($connectionlevels);
	ksort($distlevels);
	ksort($poplevels);
	$this->info("Connection counts");
	print_r($connectionlevels);
	if ($closest1sys) {
	    $this->line("Closest with <=1 connection: ".number_format($closest1)." (".$closest1sys->name.")");
	}
	$this->info("Distance counts");
	print_r($distlevels);
	$this->info("Population counts");
	print_r($poplevels);
	
    }

    public function inScope($system) {
	if ($system->population > 0) {
	    //if ($system->created_at->gt("2025-09-01")) {
	    //if ($system->created_at->gt("2025-02-01")) {
	    if ($system->created_at->gt("2025-08-01") && $system->created_at->lt("2025-09-01")) {
		$dist = $system->distance($this->sol);
		if ($dist >= 500) {
		    return true;
		}
	    }
	}
	return false;
    }

    public function boxUp(&$boxes, $system) {
	$bx = floor($system->x / 15);
	$by = floor($system->y / 15);
	$bz = floor($system->z / 15);
	$this->readyBox($boxes, $bx, $by, $bz);

	$return = false;
	if ($this->inScope($system)) {
	    // only colonies for testing
	    $boxes[$bx][$by][$bz]['in'][] = $system;
	    $return = true;
	}
	for ($x = $bx-1; $x <= $bx+1 ; $x++) {
	    for ($y = $by-1; $y <= $by+1 ; $y++) {
		for ($z = $bz-1; $z <= $bz+1 ; $z++) {
		    // all systems for nearby checks
		    $this->readyBox($boxes, $x, $y, $z);
		    $boxes[$x][$y][$z]['near'][] = $system;
		}
	    }
	}
	return $return;
    }


    public function readyBox(&$boxes, $bx, $by, $bz) {
	if (!isset($boxes[$bx])) {
	    $boxes[$bx] = [];
	}
	if (!isset($boxes[$bx][$by])) {
	    $boxes[$bx][$by] = [];
	}
	if (!isset($boxes[$bx][$by][$bz])) {
	    $boxes[$bx][$by][$bz] = [
		'in' => [],
		'near' => []
	    ];
	}
    }


    public function countNear($system, $near) {
	$count = 0;
	foreach ($near as $sys) {
	    if ($sys->id != $system->id) {
		if ($system->distance($sys) <= 15) {
		    $count++;
		}
	    }
	}
	return $count;
    }
}
