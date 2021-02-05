<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingService extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User')->orderBy('id');
    }
}
