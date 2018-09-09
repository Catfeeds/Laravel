<?php

use App\Models\ProjectApplication;
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
        factory(\App\Models\ProjectApplication::class, 200)->create();
    }
}
