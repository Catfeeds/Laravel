<?php

use App\Models\Project;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\ProjectApplication::class, function (Faker $faker) {
    do {
        $user_id = $faker->randomElement(User::where('type', 'designer')->pluck('id'));
        $project_id = $faker->randomElement(Project::pluck('id'));
    } while (\App\Models\ProjectApplication::where([
        'user_id' => $user_id,
        'project_id' => $project_id
    ])->exists());

    return [
        'user_id' => $user_id,
        'project_id' => $project_id,
        'remark' => $faker->text
    ];
});
