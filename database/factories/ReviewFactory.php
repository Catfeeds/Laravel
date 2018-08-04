<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Review::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(range(1, 20)),
        'reviewer_id' => $faker->randomElement(range(1, 20)),
        'rate' => $faker->randomElement(range(1, 5)),
        'content' => $faker->text,
    ];
});
