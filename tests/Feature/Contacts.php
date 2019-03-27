<?php
namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class Contacts extends TestCase
{
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

}
