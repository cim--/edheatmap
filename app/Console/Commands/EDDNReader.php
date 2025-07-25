<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\System;
use App\Event;
use App\Util;
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
                $timestamp = Carbon::parse($data['timestamp']);
                if ((isset($data['Factions']) && count($data['Factions']) > 0) ||
                    (isset($data['SystemEconomy']) && $data['SystemEconomy'] != '$economy_None;')
                    ) {
                    // inhabited, or if no factions, previously inhabited
                    if ($this->inBounds($data['StarPos'], $data['StarSystem'])) {
                        if ($this->duplicateCheck($event)) {
                            $system = System::where('name', $data['StarSystem'])->first();

                            if (!$system) {
                                if (!isset($data['Factions']) || count($data['Factions']) == 0) {
                                    // don't create, but continue to use existing
                                    return;
                                }
                            
                                $this->info("New system ".$data['StarSystem']);
                                $system = new System;
                                $system->id = System::max('id')+1;
                                $system->name = $data['StarSystem'];
                                $system->x = $data['StarPos'][0];
                                $system->y = $data['StarPos'][1];
                                $system->z = $data['StarPos'][2];
                                $system->population = $data['Population'];
                                $system->faction = $data['SystemFaction']['Name'] ?? "";
                                // set a default PP week
                                $system->powerplayweek = \App\Util::week($timestamp);
                                $system->save();
                            } else if ($system->population != $data['Population']) {
                                $system->population = $data['Population'];
                                $system->save();
                            }
                            //                            $this->line(print_r($data,true));
                            if ($system->faction != ($data['SystemFaction']['Name'] ?? "")) {
                                $system->faction = $data['SystemFaction']['Name'] ?? "";
                                $system->save();
                            }

                            if (isset($event['header']['gameversion']) && substr($event['header']['gameversion'],0,1) == 4) {
                                /* Only consider these if the feed is
                                 * from Live rather than Legacy */
                                
                                if (abs($timestamp->diffInMinutes()) < 10) {
                                    // not something where out of order updates are good
                                    // powerplay updates
                                    $week = \App\Util::week($timestamp);
                                    if (isset($data['ControllingPower'])) {
                                        $system->power = $data['ControllingPower'];
                                        $system->powerstate = $data['PowerplayState'];
                                        $system->powerplayweek = $week;

                                        $system->acquisition = 0;
                                        if (isset($data['PowerplayStateControlProgress'])) {
                                            $system->powercps = (int)$this->calculateCPTotal($data['PowerplayState'], $data['PowerplayStateControlProgress']);
                                        }
                                        if (isset($data['PowerplayStateReinforcement'])) {
                                            $system->reinforcement = $data['PowerplayStateReinforcement'];
                                        }
                                        if (isset($data['PowerplayStateUndermining'])) {
                                            $system->undermining = $data['PowerplayStateUndermining'];
                                        }
                                        //                                        $this->line("Undermining: ".$system->undermining);
                                        $system->setDecay();
                                        //                                        $this->line("Split to: U".$system->undermining." + D".$system->ppdecay);
                                        
                                        $system->save();
                                    } else {
                                        /* This could mean "no power" or it
                                         * could mean "player not pledged */
                                        if (isset($data['PowerplayConflictProgress'])) {
                                            // positive confirmation of Acquisition
                                            $system->power = "Acquisition";
                                            $system->powerstate = "None";
                                            $system->powerplayweek = $week;

                                            $cp = 0;
                                            foreach ($data['PowerplayConflictProgress'] as $progress) {
                                                $cp += $progress['ConflictProgress'];
                                            }
                                            $system->powercps = 0;
                                            $system->acquisition = (int)($cp * 120000);
                                            $system->reinforcement = 0;
                                            $system->undermining = 0;
                                            
                                            $system->save();

                                        } elseif ($system->power && $system->powerplayweek >= $week) {
                                            // there's been a mention of a
                                            // power presence this week already, so
                                            // ignore this one
                                        } elseif ($system->powerstate == "Stronghold" && $system->powerplayweek >= $week-1) {
                                            // can't lose a stronghold in a single week
                                            
                                        } elseif ($system->power != null) {
                                            // assume missing data and
                                            // increment the week
                                            // counter as below
                                            $system->powerplayweek = $week;
                                            $system->save();
                                            $this->line("Possible loss of ".$system->name." from Powerplay, but assuming missing data");
                                        } else {
                                            // blank to blank, update the
                                            // week number as this won't
                                            // override a switch to a
                                            // Power
                                            $system->powerplayweek = $week;
                                            $system->save();
                                        }
                                    }
                                } else {
                                    //                                    $this->line("Skipping historic data ".$data['timestamp']." received at ".$event['header']['gatewayTimestamp']." for Powerplay updates");
                                }
                            }
                            
                            $record = new Event;
                            $record->system_id = $system->id;
                            $record->eventtime = $timestamp;

                            if (isset($event['header']['gameversion']) && $event['header']['gameversion'] != "") {
                                if ($system->power != null) {
                                    // track proportion with PP info
                                    $record->version = $event['header']['gameversion']."+Powerplay";
                                } else {
                                    $record->version = $event['header']['gameversion'];
                                }
                            }
                        
                            $record->save();
                        }
                    }
                } else {
                    // not inhabited
                    if (isset($event['header']['gameversion']) && substr($event['header']['gameversion'],0,1) == 4) {
                        // only consider Live data
                        if (abs($timestamp->diffInMinutes()) < 10) {
                            // only consider recent data
                            $system = System::where('name', $data['StarSystem'])->first();
                            if ($system) {
                                $this->error("System ".$system->name." in data but appears uninhabited now.");
                                if ($system->population == 0 && $system->created_at->gt("2025-02-25")) {
                                    $system->delete();
                                    // assume failed claim
                                } else {
                                    $this->error("And was previously populated...");
                                    // assume just failure to get live data
                                    // and use of the 2.2Beta cache
                                    // so ignore it
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function inBounds($pos, $name) {
        /* // removed range limit with colonisation
          for ($i=0;$i<=2;$i++) {
            if (abs($pos[$i]) > 2000) {
                return false;
            }
            }*/

        if ($name == "L Velorum") {
            // l Velorum and L Velorum both exist,
            // for now only l Velorum is inhabited
            return false;
        }
        if ($name == "HIP 60025" && $pos[1] < 250) {
            // there are two HIP 60025s, prefer the populated one
            return false;
        }
        
        return true;
    }

    // calculate powerplay CP strength
    private function calculateCPTotal($state, $fraction) {
        if ($fraction < 0) {
            // undermined too much
            $fraction = 0;
        }
        if ($fraction > 2) {
            // integer overrun in ED journal itself!
            // probably actually undermining
            $fraction = 0;
        }
        switch ($state) {
        case "Exploited":
            return $fraction * Util::PP_EXFRAC;
        case "Fortified":
            return ($fraction * Util::PP_FOFRAC) + Util::PP_EXFRAC;
        case "Stronghold":
            return ($fraction * Util::PP_STFRAC) + Util::PP_EXFRAC + Util::PP_FOFRAC;
        }
        return 0; // shouldn't happen
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
