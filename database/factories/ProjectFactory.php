<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Project::class, function (Faker $faker) {
    return [
        'user_id'                 => $faker->randomElement([1, 3]),
        'status'                  => $faker->randomElement([500, 600, 900, 1000, 1100, 1200]),
        'title'                   => $faker->sentence,
        'types'                   => $faker->randomElements(['城市设计', '概念规划', '建筑设计', '景观设计', '室内设计'], 3),
        'features'                => $faker->randomElements(['住宅', '商业', '办公', '公共空间', '学校', '零售'], 4),
        'depth'                   => $faker->randomElement(['概念方案', '方案设计', '概念方案 + 方案设计']),
        'description'             => $faker->realText(),
//        'project_file_url'        => $faker->randomElement([null, $faker->url]),
        'delivery_time'           => $faker->randomElement(['三个月', '六个月', '其他']),
        'payment'                 => $faker->randomElement(['RMB 1,000,000.00', 'RMB 1,475,021.36', 'USD 2,681,131.60']),
        'find_time'               => $faker->randomElement(['9~12天', '12~15天', '一个月内']),
        'mode'                    => $faker->randomElement(['free', 'invite', 'specify']),
        'canceled_at'             => $faker->randomElement([null, \Carbon\Carbon::now()]),
        'remittance'              => $faker->randomElement([null, $faker->text]),
        'remittance_submitted_at' => $faker->dateTime,
        'favorite_count'          => $faker->numberBetween(10, 99),
    ];
});
