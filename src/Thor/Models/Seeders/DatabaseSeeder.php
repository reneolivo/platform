<?php

namespace Thor\Models\Seeders;

use Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Eloquent::unguard();

        // Add calls to Seeders here
        $this->call('Thor\Models\Seeders\LanguagesTableSeeder');
        $this->call('Thor\Models\Seeders\UsersTableSeeder');
        $this->call('Thor\Models\Seeders\RolesTableSeeder');
        $this->call('Thor\Models\Seeders\PermissionsTableSeeder');
    }

}
