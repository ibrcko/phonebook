<?php

class UsersTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        factory(\App\User::class, 1)->create()
            ->each(function ($u) {
                $u->contacts()->save(factory('App\Contact')->make())
                    ->each(function ($u) {
                        $u->phoneNumbers()->save(factory('App\PhoneNumber')->make());
                    });
            });
    }
}
