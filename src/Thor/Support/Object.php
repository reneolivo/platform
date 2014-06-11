<?php

namespace Thor\Support;

class Object implements \ArrayAccess
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
        if (isset($this->props[$name]) and is_callable($this->props[$name])) {
            return call_user_func_array($this->props[$name], $arguments);
        } else {
            throw \BadFunctionCallException(get_clas($this) . '::' . $name . ' method does not exist');
        }
    }

    public function __get($name)
    {
        return $this->props[$name];
    }

    public function __set($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->props[$name]);
    }

    public function __unset($name)
    {
        unset($this->props[$name]);
    }

    public function get($name)
    {
        return $this->__get($name);
    }

    public function set($name, $value)
    {
        $this->__set($name, $value);
        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->__unset($offset);
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
    public function merge(array $properties)
    {
        $this->props = array_merge($this->props, $properties);
        return $this;
    }

    /**
     * 
     * @param array $properties
     * @return static
     */
    public function import(array $properties)
    {
        $this->props = $properties;
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function export()
    {
        return $this->props;
    }

}
