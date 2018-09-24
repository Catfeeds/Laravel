<?php

use App\Handlers\ImageUploadHandler;
use App\Services\UsersService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $designerAvatar = (new UsersService())->defaultAvatar('D');
        $partyAvatar = (new UsersService())->defaultAvatar('P');
        \App\Models\User::insert([
            'name'            => 'Party A',
            'phone'           => '13000000000',
            'type'            => 'party',
            'email'           => '111@163.com',
            'email_activated' => false,
            'avatar_url' => $partyAvatar,
            'password'        => bcrypt('123123'),
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        \App\Models\User::insert([
            'name'            => 'Designer Zhu',
            'phone'           => '13000000001',
            'type'            => 'designer',
            'email'           => '123213123@163.com',
            'email_activated' => true,
            'password'        => bcrypt('123123'),
            'avatar_url' => $designerAvatar,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        \App\Models\User::insert([
            'name'            => 'Party.Andrew',
            'phone'           => '13000000002',
            'type'            => 'party',
            'email'           => '123123@163.com',
            'email_activated' => true,
            'avatar_url' => $partyAvatar,
            'password'        => bcrypt('123123'),
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        \App\Models\User::insert([
            'name'            => 'Designer Two',
            'phone'           => '13000000003',
            'type'            => 'designer',
            'email'           => '123123@163.com',
            'email_activated' => true,
            'avatar_url' => $designerAvatar,
            'password'        => bcrypt('123123'),
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
        factory(App\Models\User::class, 20)->create();
    }
}
