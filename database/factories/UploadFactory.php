<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Upload::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(User::pluck('id')),
        'type' => $faker->randomElement(['avatar', 'activity_photo', 'project_file']),
        'path' => $faker->imageUrl(300, 300)
    ];
});
