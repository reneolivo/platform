<?php

namespace Thor\Models;

use Entrust,
    Str;

/**
 * Module model 
 * @property string $name 
 * @property string $display_name 
 * @property string $icon 
 * @property text $description 
 * @property boolean $is_pageable 
 * @property string $controller_class 
 * @property string $model_class
 * @property text $metadata
 * @property boolean $is_active 
 * @property integer $sorting 
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class Module extends Base
{

    protected $table = 'modules';
    protected static $rules = array(
        'name' => 'required|not_in:user,role,permission,module,image,auth,language,main|unique:modules,name,{id}',
    );
    protected $fillable = array(
        'name', 'display_name', 'icon', 'is_pageable', 'description',
        'model_class', 'controller_class', 'is_active', 'sorting', 'metadata'
    );

    public function singular()
    {
        return $this->name;
    }

    public function plural()
    {
        return Str::plural($this->name);
    }

    public function url()
    {
        return \Backend::url($this->plural());
    }

    public function permissions()
    {
        return Permission::where('name', 'LIKE', "%_" . $this->plural())->orderBy('name', 'asc');
    }

    public function canBeListed()
    {
        return Entrust::can('list_' . $this->plural());
    }

    public function canBeCreated()
    {
        return Entrust::can('create_' . $this->plural());
    }

    public function canBeRead()
    {
        return Entrust::can('read_' . $this->plural());
    }

    public function canBeUpdated()
    {
        return Entrust::can('update_' . $this->plural());
    }

    public function canBeDeleted()
    {
        return Entrust::can('delete_' . $this->plural());
    }

    /**
     * 
     * @return static[]
     */
    public static function scopeSorted($query)
    {
        return $query->orderBy('sorting', 'asc');
    }

    /**
     * 
     * @return static[]
     */
    public static function scopeActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    /**
     * 
     * @return static
     */
    public static function scopeFromName($name)
    {
        return $query->where('name', '=', 1)->first();
    }

}
