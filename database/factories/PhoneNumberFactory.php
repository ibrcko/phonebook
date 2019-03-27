<?php

use Faker\Generator as Faker;

$factory->define(App\PhoneNumber::class, function (Faker $faker) {
    return [
        'number' => $faker->unique()->numberBetween(10000, 1000000),
        'name' => $faker->name,
        'label' => $faker->jobTitle,
    ];
});
