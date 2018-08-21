<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        \App\Models\User::insert([
            'name'            => '甲方Zhu',
            'phone'           => '15650753237',
            'type'            => 'party',
            'email'           => 'zhukaihaorj@163.com',
            'email_activated' => true,
            'password'        => bcrypt('123123'),
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        \App\Models\User::insert([
            'name'            => '设计师Zhu',
            'phone'           => '15650753236',
            'type'            => 'designer',
            'email'           => 'm15650753237@163.com',
            'email_activated' => true,
            'password'        => bcrypt('123123'),
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        factory(App\Models\User::class, 20)->create();
    }
}
