<?php

namespace App\Models;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidInputException;
use Illuminate\Support\Facades\DB;

use Symfony\Component\HttpFoundation\Response;

class Booking extends Model
{
    //start format: "Y-m-d H:i:s"
    protected $fillable = [
        'customer_id','cleaner_id', 'start', 'duration','status'
        ];

    
    
    private function validate()
    {
        $day = $this->start->format('D');
        
        if( strcasecmp($day, "Fri") === 0 )
        {
            throw new InvalidInputException("Friday is a holiday for cleaners.", Response::HTTP_PRECONDITION_FAILED);
        }
        
        if( 2 != $this->duration && 4 != $this->duration )
        {
            throw new InvalidInputException("A booking can have 2 or 4 hours duration only", Response::HTTP_PRECONDITION_FAILED);
        }
        
        
        $hour = $this->start->format('H');

        if( $hour<=8 || $hour >=22)
        {
            throw new InvalidInputException("Cleaners working hours are between 08:00 to 22:00.", Response::HTTP_PRECONDITION_FAILED);
        }
        
        $overlapping_count = $this->get_cleaner_overlapping_count();
        
        if($overlapping_count>0)
        {
            throw new InvalidInputException("Cleaner is busy during the requested time range", Response::HTTP_PRECONDITION_FAILED);
        }
        
        $overlapping_count = $this->get_customer_overlapping_count();
        
        if($overlapping_count>0)
        {
            throw new InvalidInputException("Other cleaner is workig for this customer during the requested time, Cleaners that work different companies cannot work together. ", Response::HTTP_PRECONDITION_FAILED);
        }
    }
    
    public function execute()
    {
        $this->validate();
        $this->save();
    }
    
    public function initialize($customer_id, $cleaner_id, $start, $duration, $status="booked")
    {
        $this->customer_id  = $customer_id;
        $this->cleaner_id   = $cleaner_id;
        $this->start        = new DateTime($start);//"Y-m-d H:i:s" UTC formatted with timezone
        $this->duration     = $duration;
        $this->status       = $status;
        
        return;
    }    

    
   public function get_cleaner_overlapping_count()
   {
        $start_date = $this->start;//: "Y-m-d H:i:s" 
        $end_date    = clone $start_date;
        $end_date->add(new DateInterval('PT'.(int)$this->duration.'H'));
        $start_date = $start_date->format('Y-m-d H:i:s');
        $end_date = $end_date->format('Y-m-d H:i:s');
        
        $query = "SELECT start, DATE_ADD(start, INTERVAL duration HOUR) AS end FROM bookings " . 
                " where cleaner_id = " . $this->cleaner->id. " and " ; 
      
        $query .= $this->construct_overlap($start_date, $end_date);
        
        return DB::table('bookings')
                ->select( DB::raw($query))
                ->count();
   }
    
   
   public function get_customer_overlapping_count()
   {
        $start_date = $this->start;//: "Y-m-d H:i:s" 
        $end_date    = clone $start_date;
        $end_date->add(new DateInterval('PT'.(int)$this->duration.'H'));
        $start_date = $start_date->format('Y-m-d H:i:s');
        $end_date = $end_date->format('Y-m-d H:i:s');

        
        $query = "SELECT start, DATE_ADD(start, INTERVAL duration HOUR) AS end FROM bookings " . 
                " where customer_id = " . $this->customer->id. " and " ; 
      
        $query .= $this->construct_overlap($start_date, $end_date);

        return DB::table('bookings')
                ->select( DB::raw($query))
                ->count();
   }
   
   private function construct_overlap($start_date, $end_date)
   {
    return  " HAVING (" .
                
                " ( 'start' <= ". $end_date ." AND 'end' >= ". $start_date . ")" .

                " OR ('start' >= ". $end_date . " AND 'start' <= " . $start_date . " AND 'end' <= " . $start_date . ")" .

                " OR ('end' <= " . $start_date . " AND 'end' >= " .  $end_date . " AND 'start' <= " . $end_date . ")" .

                " OR ('start' >= " . $end_date ." AND start_date <= "  . $start_date . ") )";

   }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    
    public function cleaner()
    {
        return $this->belongsTo('App\Models\Cleaner');
    }
   
   
   }

/*  constraints:   
1. Friday is a holiday for cleaners.
2. Cleaners working hours are between 08:00 to 22:00.
3. Cleaners can complete multiple bookings each day according to their availability.
4. A booking can have 2 or 4 hours duration only.
5. Every cleaner only works for a company. Cleaners that work different companies
cannot work together. 
 */     
    

   
//   Model::where(function ($query) {
//    $query->where('a', '=', 1)
//          ->orWhere('b', '=', 1);
//})->where(function ($query) {
//    $query->where('c', '=', 1)
//          ->orWhere('d', '=', 1);
//});


//        $query = "SELECT start, DATE_ADD(start, INTERVAL duration HOUR) AS end FROM bookings " . 
//                " where cleaner_id = " . $this->id. " and " . 
//                
//                " HAVING (" .
//                
//                " ( 'start' <= ". $end_date ." AND 'end' >= ". $start_date . ")" .
//
//                " OR ('start' >= ". $end_date . " AND 'start' <= " . $start_date . " AND 'end' <= " . $start_date . ")" .
//
//                " OR ('end' <= " . $start_date . " AND 'end' >= " .  $end_date . " AND 'start' <= " . $end_date . ")" .
//
//                " OR ('start' >= " . $end_date ." AND start_date <= "  . $start_date . ") )";
//
