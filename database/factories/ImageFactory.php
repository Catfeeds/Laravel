<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Image::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 10),
        'type' => $faker->randomElement(['avatar', 'activity']),
        'path' => $faker->imageUrl(300, 300)
    ];
});
