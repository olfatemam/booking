<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidInputException;
class Booking extends Model
{

    protected $fillable = [
        'customer_id','cleaner_id', 'start', 'duration','status'
        ];

    public function __constructor($customer_id, $cleaner_id, $start, $duration, $status="booked")
    {
        $this->initialize($customer_id, $cleaner_id, $start, $duration, $status);
        
    }
    
    
    private function validate()
    {
        
        $start_date = new DateTime($this->start, "YYYY-MM-DD hh:mm:ss");

        $day = $start_date->format('D');
        
        if( strcasecmp($day, "Fri") === 0 )
        {
            throw InvalidInputException("Friday is a holiday for cleaners.", Response::HTTP_PRECONDITION_FAILED, Response::HTTP_PRECONDITION_FAILED );
        }
        
        if( 2 != $this->duration && 4 != $this->duration )
        {
            throw InvalidInputException("A booking can have 2 or 4 hours duration only", Response::HTTP_PRECONDITION_FAILED, Response::HTTP_PRECONDITION_FAILED );
        }
        
        
        $hour = $start_date->format('H');

        if( $hour<=8 || $hour >=22)
        {
            throw InvalidInputException("Cleaners working hours are between 08:00 to 22:00.", Response::HTTP_PRECONDITION_FAILED, Response::HTTP_PRECONDITION_FAILED );
        }
        
        $overlapping_count = $this->get_cleaner_overlapping_count();
        
        if($overlapping_count>0)
        {
            throw InvalidInputException("Cleaner is busy during the requested time range", Response::HTTP_PRECONDITION_FAILED, Response::HTTP_PRECONDITION_FAILED );
        }
        
        $overlapping_count = $this->get_customer_overlapping_count();
        
        if($overlapping_count>0)
        {
            throw InvalidInputException("Other cleaner is workig for this customer during the requested time, Cleaners that work different companies cannot work together. ", Response::HTTP_PRECONDITION_FAILED, Response::HTTP_PRECONDITION_FAILED );
        }
    }
    
    public function execute()
    {
        $this->validate();
        $this->store();
    }
    
    private function initialize($customer_id, $cleaner_id, $start, $duration, $status)
    {
        $this->customer_id  = $customer_id;
        $this->cleaner_id   = $cleaner_id;
        $this->start        = $start;//"YYYY-MM-DD hh:mm:ss" UTC formatted with timezone
        $this->duration     = $duration;
        $this->status       = $status;
        
        return;
    }    

    
   public function get_cleaner_overlapping_count()
   {
        $start_date = new DateTime($this->start, "YYYY-MM-DD hh:mm:ss"); 
        $end_date    = clone $start_date;
        $end_date->add(new DateInterval('PT'.(int)$this->duration.'H'));

        $count = Booking::where('cleaner_id', $this->cleaner->id)
                        //reduce to this more elegant short formula: (!($b<=$c || $a>=$d)) where input (start, end)=>(a, b) and record (start, end)=>(c,d)
                        ->havingRaw(
                                " ( 'start' <= ". $end_date ." AND 'end' >= ". $start_date . ")" .
                                " OR ('start' >= ". $end_date . " AND 'start' <= " . $start_date . " AND 'end' <= " . $start_date . ")" .
                                " OR ('end' <= " . $start_date . " AND 'end' >= " .  $end_date . " AND 'start' <= " . $end_date . ")" .
                                " OR ('start' >= " . $end_date ." AND start_date <= "  . $start_date . ") )"
                            )
                ->count();
                
        return $count;
   }
    
   public function get_customer_overlapping_count()
   {
        $start_date = new DateTime($this->start, "YYYY-MM-DD hh:mm:ss"); //RFC7231
        $end_date    = clone $start_date;
        $end_date->add(new DateInterval('PT'.(int)$this->duration.'H'));

        $count = Booking::where('customer_id', $this->customer->id)
                        ->where('cleaner_id', "!=", $this->cleaner->id)
                        //olfat: reduce to this more elegant short formula: (!($b<=$c || $a>=$d)) where input (start, end)=>(a, b) and record (start, end)=>(c,d)
                        ->havingRaw(
                                " ( 'start' <= ". $end_date ." AND 'end' >= ". $start_date . ")" .
                                " OR ('start' >= ". $end_date . " AND 'start' <= " . $start_date . " AND 'end' <= " . $start_date . ")" .
                                " OR ('end' <= " . $start_date . " AND 'end' >= " .  $end_date . " AND 'start' <= " . $end_date . ")" .
                                " OR ('start' >= " . $end_date ." AND start_date <= "  . $start_date . ") )"
                            )
                ->count();
                
       return $count;
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
