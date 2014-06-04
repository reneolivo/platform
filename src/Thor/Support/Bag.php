<?php

namespace Thor\Support;

/**
 * Simple variable container with locker features
 */
class Bag extends Object
{

    /**
     *
     * @var array
     */
    protected $locked = array();
    protected $app;

    public function __construct(\Illuminate\Container\Container $app, array $properties = [])
    {
        $this->app = $app;
        parent::__construct($properties);
    }

    public function get($key, $default = null)
    {
        if(!$this->has($key)) {
            return $default;
        }
        return $this->$key;
    }

    public function set($key, $value)
    {
        if($this->locked($key)) {
            $this->throwLockedException($key);
        } else {
            $this->$key = $value;
        }
    }

    public function remove($key)
    {
        if(isset($this->data[$key])) {
            if($this->locked($key)) {
                $this->throwLockedException($key);
            } else {
                unset($this->data[$key]);
                return true;
            }
        }
        return false;
    }

    public function lock($key)
    {
        $this->locked[$key] = true;
    }

    public function unlock($key)
    {
        if(isset($this->locked[$key])) {
            unset($this->locked[$key]);
            return true;
        }
        return false;
    }

    public function locked($key)
    {
        return isset($this->locked[$key]) && ($this->locked[$key] === true);
    }

    protected function throwLockedException($key)
    {
        throw new \Exception('Variable ' . $key . ' is locked and cannot be changed.');
    }

}
