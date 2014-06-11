<?php

namespace Thor\Models\Seeders;

use Seeder,
    Thor\Platform\ThorFacade;

class RolesTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $roles = array(
            //'subscriber' => null, // normal site users, can update their profile
            //'contributor' => null, // can manage their own pages but cannot publish them
            //'author' => null, // can publish and manage their own pages
            //'editor' => null, // can publish and manage ther own and others' pages
            'administrator' => null, // has access to all administration features, plus all the above
            'developer' => null // has access to all developer and administration features
        );

        foreach ($roles as $name => $v) {
            $roles[$name] = ThorFacade::model('role')->create(array(
                'name' => $name,
                'display_name' => \Str::title(str_replace('_', ' ', $name)),
                'created_at' => $date,
                'updated_at' => $date
            ));
        }

        $developer = ThorFacade::model('user')->where('username', '=', 'developer')->first();
        $developer->attachRole($roles['developer']);

        $admin = ThorFacade::model('user')->where('username', '=', 'admin')->first();
        $admin->attachRole($roles['administrator']);
    }

}
