<?php

namespace Thor\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

/**
 * Base model with auto-validation 
 * based on https://gist.github.com/JonoB/6637861
 * 
 * @property-read int $id Primary key value
 */
abstract class Base extends Eloquent
{

    /**
     * Error message bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * General validation rules
     *
     * @var Array
     */
    protected static $rules = array();

    /**
     * Validation rules for when updating
     *
     * @var Array|null If null, default $rules array will be used
     */
    protected static $updatingRules = null;

    /**
     * Custom error messages
     *
     * @var array
     */
    protected static $messages = array();

    /**
     * 
     * @return array
     */
    public static function getValidationRules()
    {
        return static::$rules;
    }

    /**
     * 
     * @return array
     */
    public static function getValidationUpdatingRules()
    {
        return static::$updatingRules;
    }

    /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        // BEFORE:

        static::saving(function($model) {
            if($model->exists()){
                $validates = $model->validate(null, $model->getValidationUpdatingRules());
                $before = $model->beforeUpdate();
                return $validates && (($before===null) ? true : false);
            }else{
                $validates = $model->validate(null, $model->getValidationRules());
                $before = $model->beforeCreate();
                return $validates && (($before===null) ? true : false);
            }
        });

        static::deleting(function($model) {
            return $model->beforeDelete();
        });

        static::restoring(function($model) {
            return $model->beforeRestored();
        });
        
        // AFTER:

        static::created(function($model) {
            return $model->afterCreate();
        });

        static::updated(function($model) {
            return $model->afterUpdate();
        });

        static::deleted(function($model) {
            return $model->afterDelete();
        });

        static::restored(function($model) {
            return $model->afterRestore();
        });
    }

    public function beforeCreate()
    {
        return null;
    }

    public function afterCreate()
    {
        return null;
    }

    public function beforeUpdate()
    {
        return null;
    }

    public function afterUpdate()
    {
        return null;
    }

    public function beforeDelete()
    {
        return null;
    }

    public function afterDelete()
    {
        return null;
    }

    public function beforeRestore()
    {
        return null;
    }

    public function afterRestore()
    {
        return null;
    }

    /**
     * Validates current or given attributes against current or given rules and validator
     * 
     * @param array $attributes (defaults to model attributes)
     * @param array $rules (defaults to model static rules)
     * @param \Illuminate\Validation\Validator $validator (defaults to Validator facade)
     * @return boolean
     */
    public function validate(array $attributes = null, array $rules = null, Validator $validator = null)
    {
        if (!is_array($rules)) {
            $rules = static::$rules;
        }
        if (empty($validator)) {
            $validator = \App::make('validator');
        }
        $replace = ($this->getKey() > 0) ? $this->getKey() : '';
        foreach ($rules as $key => $rule) {
            $rules[$key] = str_replace('{id}', $replace, $rule);
        }
    
        $validator = $validator->make(($attributes == null) ? $this->attributes : $attributes, $rules, static::$messages);

        $this->setErrors($validator->messages());

        if ($validator->passes() and ! $this->hasErrors()) {
            return true;
        }

        return false;
    }

    public function exists()
    {
        return ($this->exists === true);
    }

    /**
     * Retrieve error message bag
     * @return Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Set error message bag
     * @param Illuminate\Support\MessageBag $errors
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Return if there are any errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return (count($this->errors) > 0);
    }

}
