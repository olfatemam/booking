<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    
    public function testBookingCreatedSuccessfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $start = date("YYYY-MM-DD hh:mm:ss");
        $bookingData = [
            "cleaner_id" => 1,
            "customer_id" => 1,
            "start" => $start,
            "duration" => 2,
        ];

        $this->json('POST', 'api/availability', $bookingData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                            "data"=>[   'error'=>0,
                                        'message'=>null,
                                        'booking'=>[
                                                "cleaner_id" => 1,
                                                "customer_id" => 1,
                                                "start" => $start,
                                                "duration" => 2]
                                        ]
                                    ]
                    );
    }

}
