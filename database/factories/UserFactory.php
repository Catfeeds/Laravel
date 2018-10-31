<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'name'         => $faker->name,
        'phone'        => $faker->unique()->phoneNumber,
        'type'         => $faker->randomElement(['client', 'designer']),
        'password'     => bcrypt('123123'),
        'title'        => $faker->company,
//        'avatar_url' => $faker->imageUrl(100, 100),
        'avatar_url'   => $faker->randomElement([
            'https://camo.githubusercontent.com/40cdecbd768f77a5ccd242c3b02817319a1b307d/687474703a2f2f6964656e7469636f6e2e6e65742f696d672f6964656e7469636f6e2e706e67',
            'https://tva2.sinaimg.cn/crop.0.0.512.512.180/7695a96djw8f3er2wyn6aj20e80e8mxg.jpg',
            'https://tvax4.sinaimg.cn/crop.0.0.1242.1242.180/005Np3d5ly8fjqcig7hjij30yi0yiq4m.jpg',
            'https://tva3.sinaimg.cn/crop.0.0.664.664.180/005tFz8hjw8f9qjdpy82cj30ig0ig3zw.jpg',
            'https://tvax4.sinaimg.cn/crop.0.0.224.224.180/005z857cly8fo27iopxn8j3068068mxa.jpg',
            'http://ww3.sinaimg.cn/square/006ItNfhly1fu87vsnnhmj30qo0qok1n.jpg',
            'http://ww3.sinaimg.cn/square/0067FVb6gy1fu832nzodnj30ty0tzn0z.jpg'
        ]),
        'introduction' => $faker->sentence,
    ];
});
