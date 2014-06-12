<?php

namespace Thor\Models;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use \InvalidArgumentException,
    Thor\Platform\ThorFacade;

class User extends \Eloquent implements UserInterface, RemindableInterface
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');
    protected $fillable = array(
        'usename', 'email', 'password', 'display_name'
    );


    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
        'username' => 'required|alpha_dash|between:4,32|unique:users,username,{id}',
        'email' => 'required|email|unique:users,email,{id}',
        'password' => 'required|min:4|confirmed',
        'password_confirmation' => 'min:4',
    );
   

    /**
     * Many-to-Many relations with Role
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Role[]
     */
    public function roles()
    {
        return $this->belongsToMany(ThorFacade::modelClass('role'), 'user_roles');
    }

    /**
     * Checks if the user has a Role by its name
     *
     * @param string $name Role name.
     * @return boolean
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the user has this roles by its name
     *
     * @param string|array $roles Set of role names.
     * @param bool $cumulative Must have all roles?
     * @param bool $returnArray Return the result as boolean array or the definitive check?
     * @return boolean|array
     */
    public function hasRoles($roles, $cumulative = true, $returnArray = false)
    {
        $hasRoles = [];

        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }

        foreach ($roles as $name) {
            if (empty($name)) {
                continue;
            }
            $hasRoles[$name] = $this->hasRole($name);
        }

        if ($returnArray) {
            return $hasRoles;
        }

        if ($cumulative && !in_array(false, $hasRoles)) {
            return true;
        } else {
            return in_array(true, $hasRoles);
        }
    }

    /**
     * Check if user has a permission by its name
     *
     * @param string $permission Permission name.
     * @return boolean
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            // Validate against the Permission table
            foreach ($role->permissions as $perm) {
                if ($perm->name == $permission) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Checks if the user has this permissions by its name
     *
     * @param string|array $permissions Set of permissions names.
     * @param bool $cumulative Must have all permissions?
     * @param bool $returnArray Return the result as boolean array or the definitive check?
     * @return boolean|array
     */
    public function hasPermissions($permissions, $cumulative = true, $returnArray = false)
    {
        $hasPermissions = [];

        if (is_string($permissions)) {
            $permissions = explode(',', $permissions);
        }

        foreach ($permissions as $name) {
            if (empty($name)) {
                continue;
            }
            $hasPermissions[$name] = $this->hasPermission($name);
        }

        if ($returnArray) {
            return $hasPermissions;
        }

        if ($cumulative && !in_array(false, $hasPermissions)) {
            return true;
        } else {
            return in_array(true, $hasPermissions);
        }
    }

    /**
     * 
     * Shortcut function for User::hasPermission
     *
     * @param string $permission Permission name.
     * @return boolean
     */
    public function can($permission)
    {
        return $this->hasPermission($permission);
    }

    /**
     * A mix of hasRoles and hasPermisions
     * Checks role(s) and permission(s) and returns bool, or array
     * @param string|array $roles Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param bool $cumulative Must have all permissions and roles?
     * @param bool $returnArray Return the result as boolean array or the definitive check?
     * @return array|bool
     * @throws InvalidArgumentException
     */
    public function ability($roles, $permissions, $cumulative = true, $returnArray = false)
    {
        $result = array(
            'roles' => $this->hasRoles($roles, $cumulative, true),
            'permissions' => $this->hasPermissions($permissions, $cumulative, true)
        );

        if ($returnArray) {
            return $result;
        }

        if ($cumulative && !in_array(false, $result['roles']) && !in_array(false, $result['permissions'])) {
            return true;
        } else {
            return (in_array(true, $result['roles']) ||  in_array(true, $result['permissions']));
        }
    }

    /**
     * Alias to eloquent many-to-many relation's
     * attach() method
     *
     * @param mixed $role
     *
     * @access public
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    /**
     * Alias to eloquent many-to-many relation's
     * detach() method
     *
     * @param mixed $role
     *
     * @access public
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }
        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    /**
     * Attach multiple roles to a user
     *
     * @param $roles
     * @access public
     * @return void
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from a user
     *
     * @param $roles
     * @access public
     * @return void
     */
    public function detachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

}
