<?php

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Review::flushEventListeners();
        factory(App\Models\Review::class, 200)->create();
    }
}
