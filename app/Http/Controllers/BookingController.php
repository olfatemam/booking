<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Resources;

use Illuminate\Http\Request;
use App\Exceptions\InvalidInputException;

use Resources\CleanerResource;
use Resources\CleanerCollection;

use Resources\BookingResource;
use Resources\BookingCollection;

use Resources\CustomerResource;
use Resources\CustomerCollection;

use App\Models\Cleaner;
use App\Models\Customer;
use App\Models\Booking;

class BookingController extends Controller
{

 public function index()
    {
        return BookingCollection::collection(Booking::paginate(5));
    }
/*
Availability check: This service should return 
 * a cleaner's list and available times. A cleaner might not be available at a particular time.
olfat: this is a check from now on if there is a booking for a certain worker we should return for that specifc day the duration when he is available
 * if not in booking then he is available with the system preconditions 
 expecting a booking date as input
 *
 * 
 * *  */
    public function availability(Request $request)
    {
        try
        {
            $available_array=array();
            $free_cleaners = Cleaner::whereNotIn('id', function($query){$query->select('cleaner_id')->from('bookings')->whereDate($request->date);})->get(); 
            $busy_that_day_cleaners = Cleaner::whereIn('id', function($query){$query->select('cleaner_id')->from('bookings')->whereDate($request->date);}); 
            foreach($free_cleaners as $cleaner)
            {
                $available_array[]=['cleaner'=>new Resources\CleanerResource($cleaner), 'bookings'=>[]];
            }
            foreach($busy_that_day_cleaners as $cleaner)
            {
                $bookings=array();
                $daybookings = $cleaner->bookings->whereDate($request->date)->get();
                foreach($daybookings as $booking)
                {
                    $bookings[]=['start'=>$booking->start, 'duration'=>$booking->duration]
                }
                $busy_that_day_cleaners[]=['cleaner'=>new Resources\CleanerResource($cleaner), 'occupied_array'=>[]];
            }
            
            //Booking();//
            //
            $booking->execute($request->customer_id, $request->start, $request->duration);

            //olfat: should do some admin work here like sending sms, email, etc... to the worker and for admin for followup
        
            return response(['data' => new BookingResource($booking)], Response::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            $html_code=($e instanceof InvalidInputException)?$e->getHtmlCode():Response::HTTP_PRECONDITION_FAILED;
            
            return response(['data' => ["Error"=>$e->getCode(), "message"=>$e->getMessage()]], $html_code );
        }
    }

    public function book(Request $request)
    {
        try
        {
            $booking = new Booking($request->customer_id, $request->start, $request->duration);//

            $booking->execute();

            //olfat: should do some admin work here like sending sms, email, etc... to the worker and for admin for followup
        
            return response(['data' => new BookingResource($booking)], Response::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            $html_code=($e instanceof InvalidInputException)?$e->getHtmlCode():Response::HTTP_PRECONDITION_FAILED;
            
            return response(['data' => ["Error"=>$e->getCode(), "message"=>$e->getMessage()]], $html_code );
        }
    }

    public function update(Request $request, Booking $booking)
    {
        try
        {
            DB::beginTransaction();
            
            $booking->delete();

            $booking = new Booking($request->customer_id, $request->start, $request->duration);//

            $booking->execute();

            //olfat: should do some admin work here like sending sms, email, etc... to the worker and for admin for followup
            
            DB::commit();

            return response(['data' => new BookingResource($booking)], Response::HTTP_CREATED);//olfat: could not find a code for resource updated,using this for now
        }
        catch(Exception $e)
        {
            DB::rollBack();
            $html_code=($e instanceof InvalidInputException)?$e->getHtmlCode():Response::HTTP_PRECONDITION_FAILED;
            
            return response(['data' => ["Error"=>$e->getCode(), "message"=>$e->getMessage()]], $html_code );
        }
    }
}
