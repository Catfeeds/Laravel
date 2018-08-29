<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Activity::class, function (Faker $faker) {
    $photoUrls = [];
    foreach (range(0, 20) as $i) {
        $photoUrls[] = $faker->imageUrl(500, 300);
    }
    return [
        'user_id' => $faker->randomElement(User::where('type', 'designer')->pluck('id')),
        'content' => $faker->text,
//        'photo_urls' => $faker->randomElements($photoUrls, 5)
    ];
});
