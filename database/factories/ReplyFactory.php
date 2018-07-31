<?php

use App\Models\User;
use App\Models\Activity;
use Faker\Generator as Faker;

$factory->define(\App\Models\Reply::class, function (Faker $faker) {
    return [
        'activity_id' => $faker->randomElement(Activity::pluck('id')),
        'user_id' => $faker->randomElement(User::pluck('id')),
        'content' => $faker->text
    ];
});
