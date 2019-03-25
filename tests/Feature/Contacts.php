<?php

namespace Tests\Feature;

use App\Contact;
use App\User;
use Tests\TestCase;

class Contacts extends TestCase
{
    public function testNotFoundContactsIndex()
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

    public function testFoundContactsIndex()
    {
        $user = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(200);

        $user->first()->contacts()->first()->delete();
        $user->first()->delete();

    }

public function testNotFoundContactsFavourite()
    {
        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.favourite.index'), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);

        $user->first()->delete();
    }

    public function testFoundContactsFavourite()
    {
        $user = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make([
                    'favourite' => 1,
                ]));
            });

        $response = $this
            ->json('GET', route('contacts.favourite.index'), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(200);

        $user->first()->contacts()->first()->delete();
        $user->first()->delete();

    }

    public function testFoundContactShow()
    {
        $user = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $response = $this
            ->json('GET', route('contacts.show', $user->first()->contacts()->first()), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(200);

        $user->first()->contacts()->first()->delete();
        $user->first()->delete();
    }

    public function testNotFoundContactShow()
    {
        $user = factory(User::class, 1)->create();

        $contactID = 99999;

        $response = $this
            ->json('GET', route('contacts.show', $contactID), ['user_id' => $user->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);

        $user->first()->delete();
    }

    public function testCreatedContactCreate()
    {
        $faker = \Faker\Factory::create();

        $user = factory(User::class, 1)->create();

        $response = $this
            ->json('POST', route('contacts.store'), [
                'user_id' => $user->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(200);

        $user->first()->delete();
    }

    public function testNotCreatedContactCreate()
    {
        $faker = \Faker\Factory::create();

        $user = factory(User::class, 1)->create();

        $contact = factory(Contact::class, 1)->create([
            'user_id' => $user->first()->id,
        ]);
        $contactEmail = $contact->first()->email;


        $response = $this
            ->json('POST', route('contacts.store'), [
                'user_id' => $user->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $contactEmail,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(422);

        if (isset($contact))
            $contact->first()->delete();

        $user->first()->delete();
    }
}
