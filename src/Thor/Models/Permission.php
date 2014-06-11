<?php

namespace Thor\Models;

use Exception,
    DB,
    Thor\Platform\ThorFacade;

class Permission extends Base
{

    protected $table = 'permissions';
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
                DB::table('role_permissions')->where('permission_id', $user->id)->delete();
            } catch (Exception $e) {
                error_log($e->getTrace());
                return false;
            }

            return true;
        });
    }

    /**
     * Many-to-Many relations with Roles
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Role[]
     */
    public function roles()
    {
        return $this->belongsToMany(ThorFacade::modelClass('role'), 'role_permissions');
    }

}
