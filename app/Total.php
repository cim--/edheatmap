<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $dates = ['created_at', 'updated_at', 'date'];

    protected $fillable = ['date'];
}
