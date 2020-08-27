<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $fillable = [
        'name','description', 'rating'
        ];

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }
    
}
