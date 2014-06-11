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

    /**
     * 
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        foreach ($this->config('models.classes') as $name => $className) {
            $this->bindModel($name, $className);
        }
    }

    /**
     * Binds a named model to the IoC, one shared and other not shared
     * @param string $name
     * @param string $className
     */
    public function bindModel($name, $className)
    {
        $makeModel = function($app, $params = array()) use($name) {
            $className = $app->make('thor.models.' . $name . '.class');
            $attributes = (isset($params[0]) ? $params[0] : array());
            $validator = (isset($params[1]) ? $params[1] : null);
            return new $className($attributes, $validator);
        };

        $this->app->bindShared('thor.models.' . $name . '.class', function() use($className) {
            return $className;
        });
        $this->app->bind('thor.models.' . $name, $makeModel);
        $this->app->bindShared('thor.models.' . $name . '.static', $makeModel);
    }

    /**
     * Returns a shared instance of the named model
     * @param string $name
     * @return \Eloquent|\Thor\Models\Base
     */
    public function model($name)
    {
        return $this->app->make('thor.models.' . $name . '.static');
    }

    /**
     * Returns the class name of the given named model (defined in thor::models.classes)
     * @param string $name
     * @return string
     */
    public function modelClass($name)
    {
        return $this->app->make('thor.models.' . $name . '.class');
    }

    /**
     * Creates a new instance of a named model
     * @param string $name
     * @param array $attributes
     * @return \Eloquent|\Thor\Models\Base
     */
    public function modelMake($name, array $attributes = array(), Validator $validator = null)
    {
        return $this->app->make('thor.models.' . $name, array('attributes' => $attributes, 'validator' => $validator));
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
