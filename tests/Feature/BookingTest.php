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
    
    public function testBookingCleanerOverlap()
    {
        $cleaner = factory(Cleaner::class)->create();
        $customer1 = factory(Customer::class)->create();
        $customer2 = factory(Customer::class)->create();

        $d=strtotime("next Monday 10:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData1 = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer1->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData1);
        $response->assertStatus(201);

        
        $d=strtotime("next Monday 11:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData2 = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer2->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData2);
        
        $response->assertStatus(412);
        
        
        var_dump($response->decodeResponseJson());
    }
    
    public function testBookingAnotherCleanerCustomerOverlap()
    {
        $cleaner1 = factory(Cleaner::class)->create();
        $cleaner2 = factory(Cleaner::class)->create();
        $customer = factory(Customer::class)->create();
        

        $d=strtotime("next Monday 10:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData1 = [
                        "cleaner_id" => $cleaner1->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData1);
        $response->assertStatus(201);

        
        $d=strtotime("next Monday 11:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData2 = [
                        "cleaner_id" => $cleaner2->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData2);
        
        $response->assertStatus(412);
        
        
        var_dump($response->decodeResponseJson());
    }

    public function testBookingOnFriday()
    {
        $cleaner = factory(Cleaner::class)->create();
        $customer = factory(Customer::class)->create();

        $d=strtotime("next Friday 10:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData);
        $response->assertStatus(412);
        var_dump($response->decodeResponseJson());
    }
    

    public function testBookingBeforeWorkingHours()
    {
        $cleaner = factory(Cleaner::class)->create();
        $customer = factory(Customer::class)->create();

        $d=strtotime("next Monday 07:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData);
        $response->assertStatus(412);
        var_dump($response->decodeResponseJson());
    }
    

    public function testBookingAfterWorkingHours()
    {
        $cleaner = factory(Cleaner::class)->create();
        $customer = factory(Customer::class)->create();

        $d=strtotime("next Monday 23:00");
        $start= date("Y-m-d H:i:s", $d);
        $bookingData = [
                        "cleaner_id" => $cleaner->id,
                        "customer_id" => $customer->id,
                        "start" => $start,
                        "duration" => 4,
                        ];

        $response  = $this->postJson('/api/bookings', $bookingData);
        $response->assertStatus(412);
        var_dump($response->decodeResponseJson());
    }
    
}
