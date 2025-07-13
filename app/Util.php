<?php
namespace App;

use Carbon\Carbon;

class Util
{

    public const PP_EXFRAC = 333333;
    public const PP_FOFRAC = 666667;
    public const PP_STFRAC = 1000000;
    public const PP_ACFIGHT = 36000;
    public const PP_ACQUIRE = 120000;
    
    // get the Powerplay week number
    public static function week($date = null)
    {
        if (!$date) {
            $date = Carbon::now();
        }
        return (int)max(0, floor($date->diffInWeeks(Carbon::parse("2024-10-24 07:10"), true)));
    }

    public static function powers()
    {
        return [
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
    }
}
