<?php

namespace Thor\Platform;

use \Illuminate\Container\Container,
    Illuminate\Validation\Validator;

class Thor
{

    /**
     *
     * @var \Illuminate\Container\Container 
     */
    protected $app;
    protected $installed = null;
    protected $modelRepository = array();

    /**
     * 
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function isInstalled()
    {
        if ($this->installed === null) {
            $this->installed = (\Schema::hasTable('languages') === true);
        }
        return $this->installed;
    }

    /**
     * Returns the class name of the given named model (defined in thor::models.classes)
     * @param string $name
     * @return string
     */
    public function modelClass($name)
    {
        return $this->config('models.classes.'.$name);
    }

    /**
     * Returns a shared instance of the named model
     * @param string $name
     * @return \Eloquent|\Thor\Models\Base
     */
    public function model($name)
    {
        $className = $this->modelClass($name);
        if(!isset($this->modelRepository[$className])){
            $this->modelRepository[$className] = new $className();
        }
        return $this->modelRepository[$className];
    }

    /**
     * Creates a new instance of a named model
     * @param string $name
     * @param array $attributes
     * @return \Eloquent|\Thor\Models\Base
     */
    public function modelMake($name, array $attributes = array(), Validator $validator = null)
    {
        $className = $this->modelClass($name);
        return new $className($attributes, $validator);
    }

    /**
     * Get the specified Thor package configuration value.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        if (empty($key)) {
            $key = 'config';
        }
        return $this->app['config']->get('thor::' . $key, $default);
    }

}
