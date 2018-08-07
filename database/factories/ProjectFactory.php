<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Project::class, function (Faker $faker) {
    return [
        'user_id'                => $faker->randomElement([1, 2]),
        'status'                 => $faker->randomElement([500, 1000, 1100, 1200]),
        'title'                  => $faker->sentence,
        'types'                  => $faker->randomElements(['城市设计', '概念规划', '建筑设计', '景观设计', '室内设计'], 3),
        'features'               => $faker->randomElements(['住宅', '商业', '办公', '公共空间', '学校', '零售'], 4),
        'area'                   => $faker->text,
        'description'            => $faker->realText(),
        'project_file_url'       => $faker->randomElement([null, $faker->url]),
        'delivery_time'          => $faker->randomElement(['三个月', '六个月', '其他']),
        'payment'                => '200万',
        'supplement_description' => $faker->text,
        'supplement_at'          =>  $faker->randomElement([null, \Carbon\Carbon::now()]),
        'find_time'              => $faker->randomElement(['9~12天', '12~15天', '一个月内']),
        'canceled_at'            => $faker->randomElement([null, \Carbon\Carbon::now()]),
        'favorite_count'         => $faker->numberBetween(10, 99),
    ];
});
