<?php

use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'profile_photo' => 'profile_photo/' . time() . '.png',
        'email' => $faker->unique()->safeEmail,
        'favourite' => rand(0,1),
    ];
});
