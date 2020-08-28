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
        
        var_dump($response->decodeResponseJson());
        
        $response->assertStatus(201);
        
    }
    
    public function stestBookingCreatedSuccessfully()
    {
        $start = date('Y-m-d H:i:s');
        $bookingData = [
            "cleaner_id" => 2,
            "customer_id" => 2,
            "start" => $start,
            "duration" => 4,
        ];

        $response  = $this->json('Post', '/bookings', $bookingData, ['Accept' => 'application/json']);
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
