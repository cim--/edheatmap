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
                if (isset($data['Factions'])) {
                    // inhabited
                    if ($this->inBounds($data['StarPos'])) {
                        $system = System::where('name', $data['StarSystem'])->first();
                        if (!$system) {
                            $this->info("New system ".$data['StarSystem']);
                            $system = new System;
                            $system->name = $data['StarSystem'];
                            $system->x = $data['StarPos'][0];
                            $system->y = $data['StarPos'][1];
                            $system->z = $data['StarPos'][2];
                            $system->save();
                        }
                        $event = new Event;
                        $event->system_id = $system->id;
                        $event->eventtime = new Carbon('now');

                        if (isset($event['header']['gameversion']) && $event['header']['gameversion'] != "") {
                            $event->version = $event['header']['gameversion'];
                        }
                        
                        $event->save();
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
}
