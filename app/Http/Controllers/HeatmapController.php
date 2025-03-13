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

        $mode = $request->input('m', 'traffic');
        switch ($timing) {
        case "colonisation":
            $m = 'colonisation';
            $mdesc = "Colonisation";
            break;
        default:
        case "traffic":
            $m = 'traffic';
            $mdesc = "System Traffic";
            break;
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
                'amount' => $system->events_count,
                'power' => $system->power,
                'cstate' => $system->colonisationState()
            ];
        }
        
        return view('index', [
            'systemdata' => base64_encode(json_encode($systemdata)),
            'systems' => $systems,
            'desc' => $tdesc,
            'mdesc' => $mdesc,
            'mode' => $m,
            'total' => $total
        ]);
    }

    public function history(Request $request) {
        $query = Total::orderBy('date');
        $start = Carbon::parse($request->input('start', '2013-01-01'));
        $end = Carbon::parse($request->input('end', 'tomorrow'));
        $query->whereDate('date', '>=', $start)
              ->whereDate('date', '<=', $end);
        $points = $query->get();

        $data = [];
        $legacy = [];
        $live = [];
        $powerplay = [];
        $original = [];
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
                $powerplay[] = [
                    'x' => $point->date->format("Y-m-d"),
                    'y' => $point->powerplay
                ];
                $original[] = [
                    'x' => $point->date->format("Y-m-d"),
                    'y' => $point->originalbubble
                ];
            }
        }

        $datasets = [
            [
                'label' => 'Jumps',
                'borderColor' => '#dddddd',
                'data' => $data
            ],
            [
                'label' => 'Legacy',
                'borderColor' => '#aaaacc',
                'backgroundColor' => 'transparent',
                'data' => $legacy
            ],
            [
                'label' => 'Live',
                'borderColor' => '#aaccaa',
                'backgroundColor' => 'transparent',
                'data' => $live
            ],
            [
                'label' => 'Powerplay',
                'borderColor' => '#ccaaaa',
                'backgroundColor' => 'transparent',
                'data' => $powerplay
            ],
            [
                'label' => 'Original Bubble',
                'borderColor' => '#aacccc',
                'backgroundColor' => 'transparent',
                'data' => $original
            ],
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
            'chart' => $chart,
            'start' => $start,
            'end' => $end
        ]);
    }
}
