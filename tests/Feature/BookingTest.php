<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

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
        $start = date('Y-m-d H:i:s');
        $bookingData = [
            "cleaner_id" => 2,
            "customer_id" => 2,
            "start" => $start,
            "duration" => 4,
        ];

        $response  = $this->postJson('/api/bookings', $bookingData);
        //$response ->dump();
        $response ->assertStatus(201);
        $response ->assertJson([
                            "data"=>[   'error'=>0,
                                        'message'=>null,
                                        'booking'=>[
                                                "cleaner_id" => 2,
                                                "customer_id" => 2,
                                                "start" => $start,
                                                "duration" => 2]
                                        ]
                                    ]
                    );
    }

}
