<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->unique()->phoneNumber,
        'type' => $faker->randomElement(['party', 'designer']),
        'password' => bcrypt('123123'),
        'title' => $faker->company,
        'introduction' => $faker->sentence,
    ];
});
