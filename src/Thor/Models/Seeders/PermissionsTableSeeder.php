<?php

namespace Thor\Models\Seeders;

use Seeder,
    Thor\Platform\ThorFacade;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $perms = array(
            'backend_access', 'generate_code',
            'list_languages', 'create_languages', 'read_languages', 'update_languages', 'delete_languages',
            'list_roles', 'create_roles', 'read_roles', 'update_roles', 'delete_roles',
            'list_permissions', 'create_permissions', 'read_permissions', 'update_permissions', 'delete_permissions',
            'list_users', 'create_users', 'read_users', 'update_users', 'delete_users'
        );
        $perms_ids = array();

        foreach ($perms as $i => $name) {
            $perms_ids[$name] = ThorFacade::model('permission')->create(array(
                        'name' => $name,
                        'display_name' => \Str::title(str_replace('_', ' ', $name)),
                        'created_at' => $date,
                        'updated_at' => $date
                    ))->id;
        }

        $administratorRole = ThorFacade::model('role')->where('name', '=', 'administrator')->first();
        $developerRole = ThorFacade::model('role')->where('name', '=', 'developer')->first();

        $administratorRole->permissions()->sync(array_merge(
                        array($perms_ids['backend_access'])
                        , $this->resourcePerms($perms_ids, 'languages')
                        , $this->resourcePerms($perms_ids, 'users')
        ));

        $developerRole->permissions()->sync(array_values($perms_ids));
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
