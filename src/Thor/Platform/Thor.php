<?php

namespace Thor\Platform;

use \Illuminate\Container\Container;

class Thor
{

    /**
     *
     * @var \Illuminate\Container\Container 
     */
    protected $app;

    /**
     *
     * @var string 
     */
    protected $namespace = 'thor';

    /**
     * 
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Get the specified Thor package configuration value.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->app['config']->get($this->namespace . '::' . $key, $default);
    }

}
