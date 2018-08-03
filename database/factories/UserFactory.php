<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->unique()->phoneNumber,
        'type' => $faker->randomElement(['party', 'designer']),
        'password' => bcrypt('123123'),
        'title' => $faker->company,
        'avatar_url' => $faker->imageUrl(100, 100),
        'introduction' => $faker->sentence,
    ];
});
