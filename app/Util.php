<?php
namespace App;

use Carbon\Carbon;

class Util
{
    // get the Powerplay week number
    public static function week($date = null)
    {
        if (!$date) {
            $date = Carbon::now();
        }
        return (int)max(0, floor($date->diffInWeeks(Carbon::parse("2024-10-24 07:00"), true)));
    }
}
