<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhoneNumbers extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testHeaders()
    {
        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => 1],[
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);

    }
}
