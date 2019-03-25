<?php
namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class Authorization extends TestCase
{
    public function testHeadersAuthorizationWithCorrectData()
    {
        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $user->first()->id],[
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);

        $user->first()->delete();
    }

    public function testHeadersAuthorizationWithWrongData()
    {
        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $user->first()->id],[
                'X-Http-Authorization' => 'Api-Secret',
                'accept' => 'application/xml',
            ]);

        $response->assertStatus(401);

        $user->first()->delete();
    }

    public function testUserAuthorizationWithNonExistingUser()
    {
        $userId = 999999;

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $userId],[
                'X-Http-Authorization' => 'Api-Secret',
                'accept' => 'application/xml',
            ]);

        $response->assertStatus(401);
    }
}
