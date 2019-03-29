<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class PhoneNumbers
 * @package Tests\Feature
 * Class that contains tests for api/phone-numbers routes
 */
class PhoneNumbers extends TestCase
{
    public function testNotFoundPhoneNumbersIndex()
    {
        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('phone-numbers.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    public function testFoundPhoneNumbersIndex()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });

        $response = $this
            ->json('GET', route('phone-numbers.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }
    /**
     * Test for found PhoneNumber on show route
     */
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
            ->json('GET', route('phone-numbers.show', $phoneNumberId), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not found PhoneNumber on show route
     */
    public function testNotFoundPhoneNumberShow()
    {
        $phoneNumberId = 99999;

        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.show', $phoneNumberId), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for created PhoneNumber on create route
     */
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
                'user_id' => $users->first()->id,
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

    /**
     * Test for not created PhoneNumber on create route because of incorrect data
     */
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
                'user_id' => $users->first()->id,
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

    /**
     * Test for not deleted PhoneNumber on destroy route because of not found PhoneNumber
     */
    public function testNotDeletedPhoneNumberDelete()
    {
        $phoneNumberID = 99999;

        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('DELETE', route('phone-numbers.destroy', $phoneNumberID), ['user_id' => $users->first()->id,], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $response->assertStatus(404);
    }

    /**
     * Test for not deleted PhoneNumber on destroy route
     */
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
            ->json('DELETE', route('phone-numbers.destroy', $phoneNumber), ['user_id' => $users->first()->id,], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not updated PhoneNumber on update route because of incorrect data
     */
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
                'user_id' => $users->first()->id,
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

    /**
     * Test for updated PhoneNumber on update route
     */
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
                'user_id' => $users->first()->id,
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

    /**
     * Test for not updated PhoneNumber on update route because of wrong method
     */
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
            ->json('POST', route('phone-numbers.update', $phoneNumber), ['user_id' => $users->first()->id,], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(405);
    }

    /**
     * Test for not updated PhoneNumber on update route because of not found PhoneNumber
     */
    public function testNotFoundContactsUpdate()
    {
        $users = factory(User::class, 1)->create();

        $phoneNumberId = 99999;

        $response = $this
            ->json('PUT', route('phone-numbers.update', $phoneNumberId), ['user_id' => $users->first()->id,], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }
}
