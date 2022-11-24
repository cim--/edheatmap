<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Total;

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
        $total = 0;
        foreach ($systems as $system) {
            $total += $system->events_count;
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
            'desc' => $tdesc,
            'total' => $total
        ]);
    }

    public function history() {
        $points = Total::orderBy('date')->get();

        $data = [];
        $legacy = [];
        $live = [];
        foreach ($points as $point) {
            $data[] = [
                'x' => $point->date->format("Y-m-d"),
                'y' => $point->total
            ];
            if ($point->legacy > 0 || $point->live > 0) {
                $legacy[] = [
                    'x' => $point->date->format("Y-m-d"),
                    'y' => $point->legacy
                ];
                $live[] = [
                    'x' => $point->date->format("Y-m-d"),
                    'y' => $point->live
                ];
            }
        }

        $datasets = [
            [
                'label' => 'Jumps',
                'borderColor' => '#999999',
                'data' => $data
            ],
            [
                'label' => 'Legacy',
                'borderColor' => '#666699',
                'backgroundColor' => 'transparent',
                'data' => $legacy
            ],
            [
                'label' => 'Live',
                'borderColor' => '#669966',
                'backgroundColor' => 'transparent',
                'data' => $live
            ]

        ];
      
        $chart = app()->chartjs
            ->name("reporthistory")
            ->type("line")
            ->size(["height" => 600, "width"=>1000])
            ->datasets($datasets)
            ->options([
                'scales' => [
                    'xAxes' => [
                        [
                            'type' => 'time',
                            'position' => 'bottom',
                        ]
                    ],
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                                'beginAtZero' => true
                            ]
                        ]
                    ]
                ]
            ]);

        return view('history', [
            'chart' => $chart
        ]);
    }
}
