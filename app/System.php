<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    public const LINK_RANGE_FORTIFIED = 20;
    public const LINK_RANGE_STRONGHOLD = 30;
    
    public function events() {
        return $this->hasMany("\App\Event");
    }
    //

    public function distance (System $a) {
        return sqrt(
            (($a->x - $this->x) * ($a->x - $this->x)) +
            (($a->y - $this->y) * ($a->y - $this->y)) +
            (($a->z - $this->z) * ($a->z - $this->z))
        );
    }

    public function range()
    {
        if ($this->powerstate == "Stronghold") {
            $range = System::LINK_RANGE_STRONGHOLD;
        } elseif ($this->powerstate == "Fortified") {
            $range = System::LINK_RANGE_FORTIFIED;
        } else {
            $range = 0;
        }
        return $range;
    }

    public function colonisationState()
    {
        if ($this->created_at->lt('2025-02-25')) {
            return "old";
        } else {
            if ($this->population > 0) {
                return "new";
            } else {
                return "claim";
            }
        }
    }

    public function setDecay()
    {
        switch ($this->powerstate) {
        case "Exploited":
            $baseline = 350000/4;
            $rate = 2/24;
            break;
        case "Fortified":
            $baseline = 350000+(650000/4);
            $rate = 4/24;
            break;
        case "Stronghold":
            $baseline = 1000000+(1000000/4);
            $rate = 5/24;
            break;
        default:
            return; // not applicable
        }

        // find the start of week strength before decay
        $originalstrength = $this->powercps + $this->undermining - $this->reinforcement;
        if ($originalstrength > $baseline) {
            // decay has occurred
            $decay = $rate * ($originalstrength - $baseline);
            // separate out decay and real undermining components
            $this->undermining = (int)max(0, $this->undermining - $decay);
            $this->ppdecay = (int)$decay;
        }
    }
}
