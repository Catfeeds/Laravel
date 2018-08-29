<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Work::class, function (Faker $faker) {
    $photoUrls = [
        'https://farm6.staticflickr.com/5591/15008867125_68a8ed88cc_b.jpg',
        'https://farm4.staticflickr.com/3902/14985871946_86abb8c56f_b.jpg',
        'https://farm4.staticflickr.com/3894/15008518202_b016d7d289_b.jpg',
        'https://farm4.staticflickr.com/3920/15008465772_383e697089_b.jpg'
    ];
//    foreach (range(0, 20) as $i) {
//        $photoUrls[] = $faker->imageUrl(500, 300);
//    }
    return [
        'user_id'     => $faker->randomElement(User::where('type', 'designer')->pluck('id')),
        'title'       => $faker->sentence,
        'description' => $faker->text,
        'photo_urls'  => $faker->randomElements($photoUrls, 4)
    ];
});
