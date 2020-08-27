<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => new Customer($this->customer),
            'cleaner' => new Cleaner($this->cleaner),
            
            //start format "YYYY-MM-DD hh:mm:ss" utc;
        
            'start' => date("YYYY-MM-DD hh:mm:ss", $this->start),
            'duration' => $this->duration,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
