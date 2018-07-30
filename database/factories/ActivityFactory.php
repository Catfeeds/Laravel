<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Activity::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(User::pluck('id')),
        'content' => $faker->text
    ];
});
