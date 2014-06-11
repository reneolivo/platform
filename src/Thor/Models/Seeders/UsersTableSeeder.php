<?php

namespace Thor\Models\Seeders;

use Seeder,
    Hash,
    Thor;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $users = array(
            array(
                'username' => 'developer',
                'display_name' => 'Developer',
                'email' => 'developer@example.com',
                'password' => Hash::make('developer'),
                'confirmed' => 1,
                'confirmation_code' => md5(microtime() . \Config::get('app.key') . 1),
                'created_at' => $date,
                'updated_at' => $date,
            ),
            array(
                'username' => 'admin',
                'display_name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'confirmed' => 1,
                'confirmation_code' => md5(microtime() . \Config::get('app.key') . 2),
                'created_at' => $date,
                'updated_at' => $date,
            )
        );

        foreach ($users as $data) {
            Thor::model('user')->create($data);
        }
    }

}
