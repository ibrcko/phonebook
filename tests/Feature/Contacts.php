<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class Contacts
 * @package Tests\Feature
 * Class that contains tests for api/contacts routes
 */
class Contacts extends TestCase
{
    /**
     * Test for not found Contact on index route
     */
    public function testNotFoundContactsIndex()
    {
        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);

    }

    /**
     * Test for Contact on index route
     */
    public function testFoundContactsIndex()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $response = $this
            ->json('GET', route('contacts.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not found Contact on favourite route
     */
    public function testNotFoundContactsFavourite()
    {
        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('GET', route('contacts.favourite.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for found Contact on favourite route
     */
    public function testFoundContactsFavourite()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make([
                    'favourite' => 1,
                ]));
            });

        $response = $this
            ->json('GET', route('contacts.favourite.index'), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for found Contact on show route
     */
    public function testFoundContactShow()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $response = $this
            ->json('GET', route('contacts.show', $users->first()->contacts()->first()), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not found Contact on show route
     */
    public function testNotFoundContactShow()
    {
        $users = factory(User::class, 1)->create();

        $contactID = 99999;

        $response = $this
            ->json('GET', route('contacts.show', $contactID), ['user_id' => $users->first()->id], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for created Contact on store route
     */
    public function testCreatedContactCreate()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('POST', route('contacts.store'), [
                'user_id' => $users->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not created Contact on store route because of incorrect data
     */
    public function testNotCreatedContactCreate()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contactEmail = $users->first()->contacts()->first()->email;

        $response = $this
            ->json('POST', route('contacts.store'), [
                'user_id' => $users->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $contactEmail,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(422);
    }

    /**
     * Test for not updated Contact on update route because of incorrect data
     */
    public function testNotUpdatedContactEdit()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contact = $users->first()->contacts()->first();
        $contactEmail = $contact->email;

        $response = $this
            ->json('PUT', route('contacts.update', $contact), [
                'user_id' => $users->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $contactEmail,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(422);
    }

    /**
     * Test for updated Contact on store route
     */
    public function testUpdatedContactEdit()
    {
        $faker = \Faker\Factory::create();

        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contact = $users->first()->contacts()->first();

        $response = $this
            ->json('PUT', route('contacts.update', $contact), [
                'user_id' => $users->first()->id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->email,
                'favourite' => rand(0, 1),
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not updated Contact on store route because of wrong method
     */
    public function testWrongMethodContactsUpdate()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contact = $users->first()->contacts()->first();

        $response = $this
            ->json('POST', route('contacts.update', $contact), [
                'user_id' => $users->first()->id,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(405);
    }

    /**
     * Test for not updated Contact on store route because of not found Contact
     */
    public function testNotFoundContactsUpdate()
    {
        $users = factory(User::class, 1)->create();

        $contactId = 99999;

        $response = $this
            ->json('PUT', route('contacts.update', $contactId), [
                'user_id' => $users->first()->id,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for not deleted Contact on destroy route because of not found Contact
     */
    public function testNotDeletedContactDelete()
    {
        $users = factory(User::class, 1)->create();

        $response = $this
            ->json('DELETE', route('contacts.destroy', 99999), [
                'user_id' => $users->first()->id,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for deleted Contact on destroy route
     */
    public function testDeletedContactDelete()
    {
        $users = factory(User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make());
            });

        $contact = $users->first()->contacts()->first();

        $response = $this
            ->json('DELETE', route('contacts.destroy', $contact), [
                'user_id' => $users->first()->id,
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for found Contacts on index search route
     */
    public function testFoundContactsSearchIndex()
    {
        $faker = \Faker\Factory::create();

        $fakeFirstName = $faker->firstName;
        $fakeLastName = $faker->lastName;

        $users = factory(User::class, 1)->create()
            ->each(function ($u) use ($fakeFirstName, $fakeLastName) {
                $u->contacts()->save(factory('App\Contact')->make(
                    [
                        'first_name' => $fakeFirstName,
                        'last_name' => $fakeLastName,
                    ]
                ));
            });

        $queries = [
            $fakeFirstName,
            $fakeLastName
        ];

        $key = array_rand($queries);

        $response = $this
            ->json('GET', route('contacts.search'), [
                'user_id' => $users->first()->id,
                'query' => $queries[$key],
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not found Contacts on index search route
     */
    public function testNotFoundContactsSearchIndex()
    {
        $faker = \Faker\Factory::create();

        $fakeFirstName = $faker->firstName;
        $fakeLastName = $faker->lastName;

        $users = factory(User::class, 1)->create();

        $queries = [
            $fakeFirstName,
            $fakeLastName,
        ];

        $key = array_rand($queries);

        $response = $this
            ->json('GET', route('contacts.search'), [
                'user_id' => $users->first()->id,
                'query' => $queries[$key],
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

    /**
     * Test for found Contacts on favourite search route
     */
    public function testFoundContactsSearchFavourites()
    {
        $faker = \Faker\Factory::create();

        $fakeFirstName = $faker->firstName;
        $fakeLastName = $faker->lastName;

        $users = factory(User::class, 1)->create()
            ->each(function ($u) use ($fakeFirstName, $fakeLastName) {
                $u->contacts()->save(factory('App\Contact')->make(
                    [
                        'first_name' => $fakeFirstName,
                        'last_name' => $fakeLastName,
                        'favourite' => 1,
                    ]
                ));
            });

        $queries = [
            $fakeFirstName,
            $fakeLastName
        ];

        $key = array_rand($queries);

        $response = $this
            ->json('GET', route('contacts.favourite.search'), [
                'user_id' => $users->first()->id,
                'query' => $queries[$key],
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(200);
    }

    /**
     * Test for not found Contacts on favourite search route
     */
    public function testNotFoundContactsSearchFavourites()
    {
        $faker = \Faker\Factory::create();

        $fakeFirstName = $faker->firstName;
        $fakeLastName = $faker->lastName;

        $users = factory(User::class, 1)->create();

        $queries = [
            $fakeFirstName,
            $fakeLastName,
        ];

        $key = array_rand($queries);

        $response = $this
            ->json('GET', route('contacts.search'), [
                'user_id' => $users->first()->id,
                'query' => $queries[$key],
            ], [
                config('auth.apiAccess.apiKey') => config('auth.apiAccess.apiSecret'),
                'accept' => 'application/json',
            ]);

        $users->first()->delete();

        $response->assertStatus(404);
    }

}
