<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\Total;
use App\System;
use Carbon\Carbon;

class Summary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heatmap:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update summary information';

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
        $date = Carbon::today();
        $backdate = Carbon::parse("-28 days");
        
        $datatotals = \DB::select("SELECT DATE(eventtime) d, COUNT(*) c FROM events WHERE DATE(eventtime) < '".$date->format("Y-m-d")."' AND DATE(eventtime) >= '".$backdate->format("Y-m-d")."' GROUP BY d");

        foreach ($datatotals as $data) {
            $total = Total::firstOrNew(
                ['date' => $data->d]
            );

            $total->date = $data->d;
            $total->total = $data->c;

            $live = \DB::select("SELECT COUNT(*) c FROM events WHERE DATE(eventtime) = '".$data->d."' AND (version LIKE '4%' OR version LIKE 'CAPI-live-%' OR (version LIKE 'CAPI-%' AND version NOT LIKE 'CAPI-legacy-%'))");
            $legacy = \DB::select("SELECT COUNT(*) c FROM events WHERE DATE(eventtime) = '".$data->d."' AND (version LIKE '3%' OR version LIKE 'CAPI-legacy-%')");

            $powerplay = \DB::select("SELECT COUNT(*) c FROM events WHERE DATE(eventtime) = '".$data->d."' AND (version LIKE '4%' OR version LIKE 'CAPI-live-%' OR (version LIKE 'CAPI-%' AND version NOT LIKE 'CAPI-legacy-%')) AND version LIKE '%+Powerplay'");

            $originalbubble = \DB::select("SELECT COUNT(*) c FROM events e INNER JOIN systems s ON (e.system_id = s.id) WHERE DATE(eventtime) = '".$data->d."' AND (version LIKE '4%' OR version LIKE 'CAPI-live-%' OR (version LIKE 'CAPI-%' AND version NOT LIKE 'CAPI-legacy-%')) AND s.created_at < '2025-02-23'");
            
            $total->live = $live[0]->c;
            $total->legacy = $legacy[0]->c;
            $total->powerplay = $powerplay[0]->c;
            $total->originalbubble = $originalbubble[0]->c;
            
            $total->save();
        }

        // cleanup
        Event::whereDate('eventtime', '<', $backdate)->delete();

        /* Set Powerplay totals */
        $total = Total::firstOrNew(
            ['date' => Carbon::today()]
        );
        if (!$total->total) {
            $total->total = 0;
        }
        $total->reinforcement = System::sum('reinforcement');
        $total->acquisition = System::sum('acquisition');
        $total->undermining = System::sum('undermining');
        $total->save();
    }

}
