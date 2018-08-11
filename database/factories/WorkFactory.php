<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $photoUrls = [];
    foreach (range(0, 20) as $i) {
        $photoUrls[] = $faker->imageUrl(500, 300);
    }
    return [
        'user_id'     => $faker->randomElement(User::pluck('id')),
        'title'       => $faker->sentence,
        'description' => $faker->text,
        'photo_urls'  => $faker->randomElements($photoUrls, 5)
    ];
});
