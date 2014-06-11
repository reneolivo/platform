<?php

namespace Thor\Support;

/**
 * Object that can have overloaded magic getters, setters, issets and unsets
 * for each individual property.
 */
class Oobject extends Object
{

    public function get($name)
    {
        $fn = 'get_' . $name;
        if(method_exists($this, $fn)) {
            return $this->$fn();
        } else {
            return $this->getProp($name);
        }
    }

    public function set($name, $value)
    {
        $fn = 'set_' . $name;
        if(method_exists($this, $fn)) {
            $this->$fn($value);
        } else {
            $this->setProp($name, $value);
        }
        return $this;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    public function __isset($name)
    {
        $fn = 'isset_' . $name;
        if(method_exists($this, $fn)) {
            return $this->$fn();
        } else {
            return parent::__isset($name);
        }
    }

    public function __unset($name)
    {
        $fn = 'unset_' . $name;
        if(method_exists($this, $fn)) {
            return $this->$fn();
        } else {
            return parent::__unset($name);
        }
    }

    /**
     * Returns the real property value WITHOUT triggering the magic getter
     * @param string $name
     * @return mixed
     */
    public function getRaw($name)
    {
        return parent::__get($name);
    }

    /**
     * Sets a property value WITHOUT triggering the magic setter
     * @param string $name
     * @return static
     */
    public function setRaw($name, $value)
    {
        parent::__set($name, $value);
        return $this;
    }

    /**
     * Imports properties USING magic getter functions (if exists)
     * @param array $properties
     * @return static
     */
    public function fromArray(array $properties)
    {
        foreach($properties as $k => $v) {
            $this->$k = $v;
        }
        return $this;
    }

    /**
     * Exports properties USING magic getter functions (if exists)
     * @return array
     */
    public function toArray()
    {
        $props = array();
        foreach($this->props as $k => $v) {
            $props[$k] = $this->$k;
        }
        return $props;
    }

    /**
     * Exports properties WITHOUT using magic getter functions
     * @return array
     */
    public function toArrayRaw()
    {
        return parent::toArray();
    }

    /**
     * Imports properties WITHOUT using magic setter functions
     * @param array $properties
     * @return static
     */
    public function fromArrayRaw(array $properties)
    {
        return parent::fromArray($properties);
    }

}
