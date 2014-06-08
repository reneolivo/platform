<?php

namespace Thor\Support;

class Object implements \ArrayAccess, \Countable
{

    /**
     * Object properties
     * @var array 
     */
    protected $props = array();

    public function __construct(array $properties = array())
    {
        $this->props = $properties;
    }

    public function __call($name, $arguments)
    {
        if(method_exists($this, $name)) {
            return call_user_func_array(array($this, $name), $arguments);
        } else if(isset($this->props[$name]) and is_callable($this->props[$name])) {
            return call_user_func_array($this->props[$name], $arguments);
        }
    }

    public function __isset($name)
    {
        return isset($this->props[$name]);
    }
    
    public function count()
    {
        return count($this->props);
    }

    public function get($name)
    {
        return $this->props[$name];
    }

    public function set($name, $value)
    {
        $this->props[$name] = $value;
        return $this;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __unset($name)
    {
        unset($this->props[$name]);
    }

    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->__unset($offset);
    }

    public function keys()
    {
        return array_keys($this->props);
    }

    public function has($key)
    {
        return $this->__isset($key);
    }

    public function isEmpty()
    {
        return empty($this->props);
    }

    /**
     * 
     * @param array $properties
     * @return static
     */
    public function fromArray(array $properties)
    {
        $this->props = array_merge($this->props, $properties);
        return $this;
    }

    public function toArray()
    {
        return $this->props;
    }

}
