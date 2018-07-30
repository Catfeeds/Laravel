<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Image::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(User::pluck('id')),
        'type' => $faker->randomElement(['avatar', 'activity']),
        'path' => $faker->imageUrl(300, 300)
    ];
});
