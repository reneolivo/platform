<?php

namespace Thor\Models\Seeders;

use Seeder,
    Hash,
    Thor\Platform\ThorFacade;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $pass1 = Hash::make('developer');
        $pass2 = Hash::make('admin');
        
        $users = array(
            array(
                'username' => 'developer',
                'display_name' => 'Developer',
                'email' => 'developer@example.com',
                'password' => $pass1,
                //'password_confirmation' => $pass1,
                'created_at' => $date,
                'updated_at' => $date,
            ),
            array(
                'username' => 'admin',
                'display_name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => $pass2,
                //'password_confirmation' => $pass2,
                'created_at' => $date,
                'updated_at' => $date,
            )
        );

        foreach ($users as $data) {
            ThorFacade::model('user')->create($data);
        }
    }

}
