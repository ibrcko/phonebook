<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class PhoneNumbers extends TestCase
{
    public function testFoundPhoneNumberShow()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $phoneNumberId = $users->first()->contacts()->first()->phoneNumbers()->first()->id;

        $response = $this
            ->json('GET', route('phone-numbers.show', $phoneNumberId), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    public function testNotFoundPhoneNumberShow()
    {
        $phoneNumberId = 99999;

        $response = $this
            ->json('GET', route('contacts.show', $phoneNumberId), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);
    }

    public function testCreatedPhoneNumberCreate()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contactId = $users->first()->contacts()->first()->id;

        $response = $this
            ->json('POST', route('phone-numbers.store'), [
                'contact_id' => $contactId,
                'number' => $faker->unique()->numberBetween(10000, 1000000),
                'name' => $faker->name,
                'label' => $faker->jobTitle,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    public function testNotCreatedPhoneNumberCreate()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $contactId = $users->first()->contacts()->first()->id;
        $phoneNumberNumber = $users->first()->contacts()->first()->phoneNumbers()->first()->number;

        $response = $this
            ->json('POST', route('phone-numbers.store'), [
                'contact_id' => $contactId,
                'number' => $phoneNumberNumber,
                'name' => $faker->name,
                'label' => $faker->jobTitle,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(422);
    }

    public function testNotDeletedPhoneNumberDelete()
    {
        $phoneNumberID = 99999;

        $response = $this
            ->json('DELETE', route('phone-numbers.destroy', $phoneNumberID), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);
    }

    public function testDeletedPhoneNumberDelete()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $phoneNumber = $users->first()->contacts()->first()->phoneNumbers()->first();

        $response = $this
            ->json('DELETE', route('phone-numbers.destroy', $phoneNumber), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    public function testNotUpdatedPhoneNumberEdit()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $phoneNumber = $users->first()->contacts()->first()->phoneNumbers()->first();
        $phoneNumberNumber = $phoneNumber->number;

        $response = $this
            ->json('PUT', route('phone-numbers.update', $phoneNumber), [
                'number' => $phoneNumberNumber,
                'name' => $faker->name,
                'label' => $faker->jobTitle,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(422);
    }

    public function testUpdatedContactEdit()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $phoneNumber = $users->first()->contacts()->first()->phoneNumbers()->first();

        $response = $this
            ->json('PUT', route('phone-numbers.update', $phoneNumber), [
                'number' => $faker->unique()->numberBetween(10000, 1000000),
                'name' => $faker->name,
                'label' => $faker->jobTitle,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    public function testWrongMethodContactsUpdate()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $phoneNumber = $users->first()->contacts()->first()->phoneNumbers()->first();

        $response = $this
            ->json('POST', route('phone-numbers.update', $phoneNumber), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(405);
    }

    public function testNotFoundContactsUpdate()
    {
        $users = factory(User::class, 1)->create();

        $phoneNumberId = 99999;

        $response = $this
            ->json('PUT', route('phone-numbers.update', $phoneNumberId), [], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }
}
