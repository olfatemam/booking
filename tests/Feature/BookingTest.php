<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class BookingTest extends TestCase
{
    
    public function testBookingCreatedSuccessfully()
    {
        $start = date("YYYY-MM-DD hh:mm:ss");
        $bookingData = [
            "cleaner_id" => 1,
            "customer_id" => 1,
            "start" => $start,
            "duration" => 2,
        ];

        $response  = $this->json('Post', '/public/api/bookings', $bookingData, ['Accept' => 'application/json']);
        $response->dumpHeaders();
        $response->dumpSession();
        $response->dump();
        //Log::info(print_r(, true));
        //$response->streamedContent();
        //Log::info($response->dumpSession());

        //Log::info(print_r($response->dump(), true));
        
        //Log::info(print_r($ret, false));
        
        
//        
//            ->assertStatus(201)
//            ->assertJson([
//                            "data"=>[   'error'=>0,
//                                        'message'=>null,
//                                        'booking'=>[
//                                                "cleaner_id" => 1,
//                                                "customer_id" => 1,
//                                                "start" => $start,
//                                                "duration" => 2]
//                                        ]
//                                    ]
//                    );
    }

}
