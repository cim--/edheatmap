<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeatmapController extends Controller
{
    public function index() {
        $systems = \App\System::withCount('events')->get();

        $systems = $systems->sortByDesc('events_count');
        
        $systemdata = [];
        foreach ($systems as $system) {
            $systemdata[] = [
                'name' => $system->name,
                'x' => $system->x,
                'y' => $system->y,
                'z' => $system->z,
                'amount' => $system->events_count
            ];
        }
        
        return view('index', [
            'systemdata' => base64_encode(json_encode($systemdata)),
            'systems' => $systems
        ]);
    }
}
