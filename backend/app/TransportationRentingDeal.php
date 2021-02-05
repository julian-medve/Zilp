<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportationRentingDeal extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User')->orderBy('id');
    }
}
