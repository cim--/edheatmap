<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\System;
use Storage;

class PowerController extends Controller
{
    public function week(Carbon $date) {
        return \App\Util::week($date);
    }
    
    public function index()
    {
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
        return view('powers.index', [
            'powers' => $powers,
            'week' => $this->week(Carbon::now())
        ]);
    }

    public function maps($power, $week)
    {
        $week = (int)$week;
        if ($week == 0 && $power == "Jerome Archer") {
            $power = "Zachary Hudson";
        }

        $powerfile = preg_replace("/[^A-Za-z-]/", "", $power);
        $f1 = $week."/".$powerfile.".hierarchy.png";
        $f2 = $week."/".$powerfile.".connections.png";

        if (\Storage::disk('public')->exists($f1) && \Storage::disk('public')->exists($f2)) {
            return view('powers.maps', [
                'hierarchy' => $f1,
                'connection' => $f2,
                'week' => $week,
                'power' => $power
            ]);
        } else {
            abort(404);
        }
    }

    public function control(Request $request) {
        $sysname = $request->input('system');
        $system = System::where('name', $sysname)->whereIn('powerstate', ['Stronghold', 'Fortified'])->first();
        if (!$system) {
            return view('powers.control.bad', [
                'sysname' => $sysname
            ]);
        }
        $power = $system->power;

        $controls = System::where('name', '!=', $sysname)->whereIn('powerstate', ['Stronghold', 'Fortified'])->where('power', $power)->orderBy('name')->get();
        $exploited = System::where('name', '!=', $sysname)->whereIn('powerstate', ['Exploited'])->where('power', $power)->orderBy('name')->get();

        $range = $system->range();

        $exlist = [];
        foreach ($exploited as $exsys) {
            if ($system->distance($exsys) <= $range) {
                $fortlist = [];
                foreach ($controls as $csys) {
                    if ($csys->distance($exsys) <= $csys->range()) {
                        $fortlist[] = $csys;
                    }
                }
                $exlist[$exsys->name] = $fortlist;
            }
        }

        return view('powers.support', [
            'power' => $power,
            'system' => $system,
            'exlist' => $exlist,
        ]);
    }

    private function distance2($e, $f) {
        return (($e->x - $f->x)**2) + (($e->y - $f->y)**2) + (($e->z - $f->z)**2);
    }
    
    private function findLoose($power, $home)
    {
        $loose = [];
        $exploiteds = System::where('power', $power)->where('powerstate', 'Exploited')->get();
        $fortifieds = System::where('power', $power)->whereIn('powerstate', ['Fortified', 'Stronghold'])->get();
        foreach ($exploiteds as $exploited) {
            $found = false;
            foreach ($fortifieds as $fortified) {
                $dist = $this->distance2($exploited, $fortified);
                if ($dist <= 400 || ($dist <= 900 && $fortified->powerstate == "Stronghold")) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $loose[] = $exploited;
            }
        }
        return $loose;
    }
    
    public function looseSystems()
    {
        $powers = \App\Util::powers();
        $loose = [];
        foreach ($powers as $power => $home) {
            $loose[$power] = $this->findLoose($power, $home);
        }
        return view('powers.loose', [
            'powers' => $powers,
            'loose' => $loose
        ]);
    }
}
