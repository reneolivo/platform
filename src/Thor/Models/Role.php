<?php

namespace Thor\Models;

use Exception,
    DB;

class Role extends Base
{

    protected $table = 'roles';
    protected $fillable = array(
        'name', 'display_name', 'description'
    );
    protected $hidden = array(
        'id'
    );

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|alpha_dash|between:4,128|unique:roles,name,{id}',
    );

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            try {
                DB::table('user_roles')->where('role_id', $user->id)->delete();
                DB::table('role_permissions')->where('role_id', $user->id)->delete();
            } catch (Exception $e) {
                error_log($e->getTrace());
                return false;
            }

            return true;
        });
    }

    /**
     * Many-to-Many relations with Users
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|User[]
     */
    public function users()
    {
        return $this->belongsToMany(Thor::modelClass('user'), 'user_roles');
    }

    /**
     * Many-to-Many relations with Permission
     * named perms as permissions is already taken.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Permission[]
     */
    public function permissions()
    {
        return $this->belongsToMany(Thor::modelClass('permission'), 'role_permissions');
    }

    /**
     * Save permissions inputted
     * @param int[] $inputPermissions
     */
    public function updatePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->permissions()->sync($inputPermissions);
        } else {
            $this->permissions()->detach();
        }
    }

    /**
     * Attach permission to current role
     * @param int|Base $permission
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->permissions()->attach($permission);
    }

    /**
     * Detach permission form current role
     * @param int|Base $permission
     */
    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->permissions()->detach($permission);
    }

    /**
     * Attach multiple permissions to current role
     *
     * @param array $permissions
     */
    public function attachPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param array $permissions
     */
    public function detachPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

}
