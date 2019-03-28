<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class Authorization
 * @package Tests\Feature
 * Class that contains tests for API authorization
 */
class Authorization extends TestCase
{
    /**
     * Test correct headers and existing user
     */
    public function testHeadersAuthorizationWithCorrectData()
    {
        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);

        $user->first()->delete();
    }

    /**
     * Test incorrect headers and existing user
     */
    public function testHeadersAuthorizationWithWrongData()
    {
        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $user->first()->id], [
                'X-Http-Authorization' => 'Api-Secret',
                'accept' => 'application/xml',
            ]);

        $response->assertStatus(401);

        $user->first()->delete();
    }

    /**
     * Test wrong headers and non-existing user
     */
    public function testUserAuthorizationWithNonExistingUser()
    {
        $userId = 999999;

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $userId], [
                'X-Http-Authorization' => 'Api-Secret',
                'accept' => 'application/xml',
            ]);

        $response->assertStatus(401);
    }
}
