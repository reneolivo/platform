<?php

namespace Thor\Models\Seeders;

use Seeder,
    Thor;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $perms = array(
            'access_backend',
            'list_languages', 'create_languages', 'read_languages', 'update_languages', 'delete_languages',
            'list_pages', 'create_pages', 'read_pages', 'update_pages', 'delete_pages',
            'list_roles', 'create_roles', 'read_roles', 'update_roles', 'delete_roles',
            'list_permissions', 'create_permissions', 'read_permissions', 'update_permissions', 'delete_permissions',
            'list_users', 'create_users', 'read_users', 'update_users', 'delete_users',
            'list_modules', 'create_modules', 'read_modules', 'update_modules', 'delete_modules',
        );
        $perms_ids = array();

        foreach ($perms as $i => $name) {
            $perms_ids[$name] = Thor::model('permission')->create(array(
                        'name' => $name,
                        'display_name' => \Str::title(str_replace('_', ' ', $name)),
                        'created_at' => $date,
                        'updated_at' => $date
                    ))->id;
        }

        $administratorRole = Thor::model('user')->where('name', '=', 'administrator')->first();
        $developerRole = Thor::model('user')->where('name', '=', 'developer')->first();

        $administratorRole->perms()->sync(array_merge(
                        array($perms_ids['access_backend'])
                        , $this->resourcePerms($perms_ids, 'languages')
                        , $this->resourcePerms($perms_ids, 'pages')
                        , $this->resourcePerms($perms_ids, 'users')
        ));

        $developerRole->perms()->sync(array_values($perms_ids));
    }

    protected function resourcePerms($perms, $plural)
    {
        return array(
            $perms['list_' . $plural],
            $perms['create_' . $plural],
            $perms['read_' . $plural],
            $perms['update_' . $plural],
            $perms['delete_' . $plural]
        );
    }

}
