<?php

namespace Thor\Models\Seeders;

use Seeder,
    \Thor\Models\Module;

class ModulesTableSeeder extends Seeder
{/**
 * Module model 
 * @property string $name 
 * @property string $display_name 
 * @property string $icon 
 * @property text $description 
 * @property boolean $is_pageable 
 * @property boolean $is_translatable 
 * @property boolean $is_imageable 
 * @property boolean $is_active 
 * @property integer $sorting 
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('modules')->delete();

        Module::create(array(
            'name' => 'page',
            'display_name' => 'Pages',
            'icon' => 'fa-bookmark',
            'description' => 'Pages module',
            'is_pageable' => true,
            'is_translatable' => true,
            'is_imageable' => true,
            'is_active' => true,
            'sorting' => 1
        ));
    }

}
