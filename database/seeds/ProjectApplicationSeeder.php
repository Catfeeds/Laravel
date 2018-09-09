<?php

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectApplication::flushEventListeners();
//        factory(\App\Models\ProjectApplication::class, 100)->create();

        $faker = Faker\Factory::create();

        for($i = 0; $i < 100; $i++) {
            do {
                $user_id = $faker->randomElement(User::where('type', 'designer')->pluck('id'));
                $project_id = $faker->randomElement(Project::where('mode', 'free')->pluck('id'));
            } while (\App\Models\ProjectApplication::where([
                'user_id'    => $user_id,
                'project_id' => $project_id
            ])->exists());
            ProjectApplication::create([
                'user_id'    => $user_id,
                'project_id' => $project_id,
                'remark'     => $faker->text
            ]);
        }
    }
}
