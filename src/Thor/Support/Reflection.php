<?php

namespace Thor\Support;

class Reflection {

    public static function create($className, array $args = array()) {
        $rClass = new \ReflectionClass($className);
        return $rClass->newInstanceArgs($args);
    }

    /**
     * Takes a classname and returns the actual classname for an alias or just the classname
     * if it's a normal class.
     *
     * @param   string  classname to check
     * @return  string  real classname
     */
    public static function getRealClass($class) {
        static $classes = array();

        if (!array_key_exists($class, $classes)) {
            $reflect = new \ReflectionClass($class);
            $classes[$class] = $reflect->getName();
        }

        return $classes[$class];
    }

    /**
     * Gets all the public vars for an object.  Use this if you need to get all the
     * public vars of $this inside an object.
     *
     * @return	array
     */
    public static function getPublicVars($obj) {
        return get_object_vars($obj);
    }

    /**
     * Retrieves all constants (or the specified one) from a class using Reflection
     * 
     * @param string $className
     * @param string $constantName specific constant value
     * @return mixed 
     */
    public static function getConstants($className = null, $constantName = null) {
        if (empty($className)) {
            $className = get_called_class();
        }
        $reflect = new \ReflectionClass($className);
        $constants = $reflect->getConstants();

        if (!empty($constantName)) {
            return $constants[$constantName];
        } else {
            return $constants;
        }
    }

    /**
     * 
     * @param mixed $var
     * @return string primitive type or class name
     */
    public static function getType($var) {
        return is_object($var) ? get_class($var) : gettype($var);
    }

}
