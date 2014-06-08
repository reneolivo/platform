<?php

namespace Thor\Models\Seeders;

use Seeder,
    DB,
    Hash,
    DateTime;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $users = array(
            array(
                'username' => 'developer',
                'email' => 'developer@thorcms.com',
                'password' => Hash::make('developer'),
                'confirmed' => 1,
                'confirmation_code' => md5(microtime() . \Config::get('app.key') . 1),
                'created_at' => $date,
                'updated_at' => $date,
            ),
            array(
                'username' => 'admin',
                'email' => 'admin@thorcms.com',
                'password' => Hash::make('admin'),
                'confirmed' => 1,
                'confirmation_code' => md5(microtime() . \Config::get('app.key') . 2),
                'created_at' => $date,
                'updated_at' => $date,
            )
        );

        DB::table('users')->insert($users);
    }

}
