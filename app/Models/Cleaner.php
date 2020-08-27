<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cleaner extends Model
{

    protected $fillable = [
        'name','description', 'rating'
        ];

    const Cleaner_Available     =   "Cleaner Available";
    const Cleaner_Booked        =   "Cleaner Booked";
    const Cleaner_Unavailable   =  "Cleaner Unavailable";

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }    

    
   

  
}
