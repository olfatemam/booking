<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use \App\Models\Cleaner;
use App\Models\Customer;

/*
test cases to cover:
 * 1. successfully create booking 
 * 2. handling cleaner overlap
 * 3. handle other cleaner overlap with the cleaner during the required time with the same customer
 * handling fridays case
 * handling working hours case
 * 
TBD: 
 *  *  */

class BookingTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->get('/api/availability');
        
        Log::info(print_r((array)$response->decodeResponseJson(), true));
        
        var_dump($response->decodeResponseJson());
        
        $response->assertStatus(200);
        
    }
    
    public function testBookingCreatedSuccessfully()
    {
        $cleaner = factory(Cleaner::class)->create();
        $customer = factory(Customer::class)->create();

        $d=strtotime("next Monday 10:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData);
        $response->assertStatus(201);
        var_dump($response->decodeResponseJson());
    }

}
