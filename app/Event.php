<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function system() {
        return $this->belongsTo('\App\System');
    }
    //
}
