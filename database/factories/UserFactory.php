<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->unique()->phoneNumber,
        'password' => bcrypt('123123')
    ];
});
