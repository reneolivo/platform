<?php

namespace Thor\Models\Seeders;

use Seeder, Backend,
    \Thor\Models\User,
    \Thor\Models\Role,
    \Thor\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $perms = array(
            Backend::ACCESS_PERMISSION_NAME,
            'list_languages', 'create_languages', 'read_languages', 'update_languages', 'delete_languages',
            'list_pages', 'create_pages', 'read_pages', 'update_pages', 'delete_pages',
            'list_roles', 'create_roles', 'read_roles', 'update_roles', 'delete_roles',
            'list_permissions', 'create_permissions', 'read_permissions', 'update_permissions', 'delete_permissions',
            'list_users', 'create_users', 'read_users', 'update_users', 'delete_users',
            'list_modules', 'create_modules', 'read_modules', 'update_modules', 'delete_modules',
        );
        $perms_ids = array();

        foreach ($perms as $i => $name) {
            $perms_ids[$name] = Permission::create(array(
                        'name' => $name,
                        'display_name' => \Str::title(str_replace('_', ' ', $name)),
                        'created_at' => $date,
                        'updated_at' => $date
                    ))->id;
        }

        $administratorRole = Role::where('name', '=', 'administrator')->first();
        $developerRole = Role::where('name', '=', 'developer')->first();

        $administratorRole->perms()->sync(array_merge(
                        array($perms_ids[Backend::ACCESS_PERMISSION_NAME]),
                        $this->resourcePerms($perms_ids, 'languages'),
                        $this->resourcePerms($perms_ids, 'pages'),
                        $this->resourcePerms($perms_ids, 'users')
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
