<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HeatmapController extends Controller
{
    public function index(Request $request) {
        $timing = $request->input('t', 'week');
        switch ($timing) {
        case "hour":
            $t = 3600;
            $tdesc = "Last Hour";
            break;
        case "day":
            $t = 86400;
            $tdesc = "Last Day";
            break;
        default:
        case "week":
            $t = 86400 * 7;
        $tdesc = "Last Week";
        }

        $time = new Carbon('-'.$t.' seconds');
        
        $getsystems = \App\System::withCount(['events' => function($q) use ($time) {
                $q->where('eventtime', '>', $time);
            }]);
        
        $systems = $getsystems->get()->sortByDesc('events_count');
        
        $systemdata = [];
        foreach ($systems as $system) {
            $systemdata[] = [
                'name' => $system->name,
                'x' => $system->x,
                'y' => $system->y,
                'z' => -$system->z,
                'amount' => $system->events_count
            ];
        }
        
        return view('index', [
            'systemdata' => base64_encode(json_encode($systemdata)),
            'systems' => $systems,
            'desc' => $tdesc
        ]);
    }
}
