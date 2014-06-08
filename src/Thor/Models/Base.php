<?php

namespace Thor\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator,
    App;

/**
 * Base model with auto-validation 
 * based on https://gist.github.com/JonoB/6637861
 * 
 * @property-read int $id Primary key value
 */
abstract class Base extends Eloquent {

    /**
     * Error message bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     *
     * @var Array
     */
    protected static $rules = array();

    /**
     * Custom error messages
     *
     * @var array
     */
    protected static $messages = array();

    /**
     * Validator instance
     *
     * @var Illuminate\Validation\Validators
     */
    protected $validator;

    /**
     *
     * @var array 
     */
    //protected $guarded = array();

    public function __construct(array $attributes = array(), Validator $validator = null) {
        parent::__construct($attributes);

        $this->validator = $validator ? : App::make('validator');
    }

    /**
     * Listen for save event
     */
    protected static function boot() {
        parent::boot();

        static::saving(function($model) {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate(array $attributes = null) {
        $replace = ($this->getKey() > 0) ? $this->getKey() : '';
        foreach (static::$rules as $key => $rule) {
            static::$rules[$key] = str_replace('{id}', $replace, $rule);
        }

        $validator = $this->validator->make(($attributes==null) ? $this->attributes : $attributes, static::$rules, static::$messages);

        $this->setErrors($validator->messages());

        if ($validator->passes() and !$this->hasErrors()) {
            return true;
        }

        return false;
    }

    /**
     * Returns the associated module (if this model has been created through 'Modules')
     * @return Module|false
     */
    public static function relatedModule() {
        $kl = trim(get_class(new static()),'/\\');
        foreach(\Backend::modules() as $m){
            if(trim($m->model_class, '/\\') == trim($kl, '/')){
                return $m;
            }
        }
        return false;
    }

    public function exists() {
        return ($this->exists === true);
    }

    /**
     * Retrieve error message bag
     * @return Illuminate\Support\MessageBag
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Set error message bag
     * @param Illuminate\Support\MessageBag $errors
     */
    protected function setErrors($errors) {
        $this->errors = $errors;
    }

    /**
     * Return if there are any errors
     *
     * @return bool
     */
    public function hasErrors() {
        return (count($this->errors) > 0);
    }

}
