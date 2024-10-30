<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\System;
use App\Event;
use Carbon\Carbon;

class EDDNReader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heatmap:read';

    private $relay = 'tcp://eddn.edcd.io:9500';

    private $curdup = [];
    private $lastdup = [];
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read system entry data from EDDN';

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
        $context    = new \ZMQContext();
        $subscriber = $context->getSocket(\ZMQ::SOCKET_SUB);
        $subscriber->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, "");
        $subscriber->setSockOpt(\ZMQ::SOCKOPT_RCVTIMEO, 600000);

        while (true) {
            try {
                
                $subscriber->connect($this->relay);
                $this->info("EDDN Connection Online");
                while (true) {
                    $message = $subscriber->recv();
                    
                    if ($message === false) {
                        $this->error("Connection timeout on socket - reconnecting");
                        $subscriber->disconnect($this->relay);
                        break;
                    }
                    
                    $message    = zlib_decode($message);
                    $json       = $message;
                    
                    $this->process(json_decode($json, true));
                }
            } catch (\ZMQSocketException $e) {
                $this->error('ZMQSocketException: ' . $e);
                sleep(10);
            }
        }        
        
    }

    private function process($event)
    {
        if ($event['$schemaRef'] == "http://schemas.elite-markets.net/eddn/journal/1" || $event['$schemaRef'] == "https://eddn.edcd.io/schemas/journal/1") {
            if ($event['message']['event'] == "FSDJump") {
                $data = $event['message'];
                if ((isset($data['Factions']) && count($data['Factions']) > 0) ||
                    (isset($data['SystemEconomy']) && $data['SystemEconomy'] != '$economy_None;')
                    ) {
                    // inhabited, or if no factions, previously inhabited
                    if ($this->inBounds($data['StarPos'])) {

                        if ($this->duplicateCheck($event)) {
                            $system = System::where('name', $data['StarSystem'])->first();
                            if (!$system) {
                                if (!isset($data['Factions']) || count($data['Factions']) == 0) {
                                    // don't create, but continue to use existing
                                    return;
                                }
                            
                                $this->info("New system ".$data['StarSystem']);
                                $system = new System;
                                $system->name = $data['StarSystem'];
                                $system->x = $data['StarPos'][0];
                                $system->y = $data['StarPos'][1];
                                $system->z = $data['StarPos'][2];
                            }
                            //                            $this->line(print_r($data,true));
                            // powerplay updates
                            if (isset($data['ControllingPower'])) {
                                $system->power = $data['ControllingPower'];
                                $system->powerstate = $data['PowerplayState'];
                            } else {
                                // don't blank just yet until
                                // there's more EDDN data available
                                //  $system->power = null;
                                //  $system->powerstate = "None";
                            }

                            $system->save();
                            
                            $record = new Event;
                            $record->system_id = $system->id;
                            $record->eventtime = new Carbon('now');

                            if (isset($event['header']['gameversion']) && $event['header']['gameversion'] != "") {
                                $record->version = $event['header']['gameversion'];
                            }
                        
                            $record->save();
                        }
                    }
                }
            }
        }
    }

    private function inBounds($pos) {
        for ($i=0;$i<=2;$i++) {
            if (abs($pos[$i]) > 500) {
                return false;
            }
        }
        return true;
    }

    private function duplicateCheck($event) {
        // most cases where timestamp and starsystem match are going
        // to be duplicates
        
        $hash = md5(
            $event['message']['StarSystem'].'/'.
            ($event['message']['timestamp']??'-').'/'.
            ($event['message']['horizons']??'-').'/'.
            ($event['message']['odyssey']??'-').'/'.
            ($event['header']['gameversion']??'-')
        );

        if (isset($this->curdup[$hash]) || isset($this->lastdup[$hash])) {
            return false;
        } else {
            $this->curdup[$hash] = $hash;
            if (count($this->curdup) >= 1000) {
                $this->lastdup = $this->curdup;
                $this->curdup = [];
            }
            return true;
        }
    }
}
