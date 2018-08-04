<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::insert( [
            'name' => 'Zhu',
            'phone' => '15650753237',
            'type' => 'party',
            'password' => bcrypt('123123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        factory(App\Models\User::class, 50)->create();
    }
}
