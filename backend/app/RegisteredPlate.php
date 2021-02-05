<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisteredPlate extends Model
{
    protected $attributes = [
        'is_owner' => false,
        'access_code' => null
    ];

    protected $appends = [
        'inUseBy',
        'isPrimary'
    ];

    public function getInUseByAttribute()
    {
        $this_plate = RegisteredPlate::where('id', $this->id)->first();

        $user = User::select([
            'id',
            'plate_number AS plateNumber',
            'first_name AS firstName',
            'last_name AS lastName'
        ])->where('guest_plate', $this_plate->plate_number)
            ->first();

        if ($user) {
            return $user;
        }

        return false;
    }

    public function getIsPrimaryAttribute()
    {
        $this_plate = RegisteredPlate::where('id', $this->id)->first();

        return $this_plate->plate_number == auth()->user()->plate_number;
    }

    public function user()
    {
        return $this->belongsTo('App\User')->orderBy('id', 'desc');
    }
}
