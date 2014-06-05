<?php

if(!function_exists('lang_url')) {

    /**
     * Decode html code (HTML::decode alias)
     * @param string $value
     * @return string
     */
    function lang_url($path = '', $extra = array(), $secure = null, $langCode = null)
    {
        return URL::langTo($path, $extra, $secure, $langCode);
    }

}

if(!function_exists('_d')) {

    /**
     * Decode html code (HTML::decode alias)
     * @param string $value
     * @return string
     */
    function _d($value)
    {
        return HTML::decode($value);
    }

}



if(!function_exists('get_class_constants')) {

    /**
     * Retrieves all constants (or the specified one) from a class using Reflection
     * 
     * @param string $className
     * @param string $constantName If you want to return an specific constant name
     * @return mixed 
     */
    function get_class_constants($className, $constantName = null)
    {
        $reflect = new \ReflectionClass($className);
        $constants = $reflect->getConstants();

        if(!empty($constantName)) {
            return $constants[$constantName];
        } else {
            return $constants;
        }
    }

}

if(!function_exists('get_object_public_vars')) {


    /**
     * Gets all the public vars for an object. Useful when using $this as the value.
     *
     * @param mixed $obj
     * @return	array
     */
    function get_object_public_vars($obj)
    {
        return get_object_vars($obj);
    }

}

if(!function_exists('get_real_type')) {

    /**
     * 
     * @param mixed $var
     * @return string primitive type or class name
     */
    function get_real_type($var)
    {
        return is_object($var) ? get_class($var) : gettype($var);
    }

}

if(!function_exists('get_real_class')) {

    /**
     * Takes a classname and returns the actual classname for an alias or just the classname
     * if it's a normal class.
     *
     * @param   string  classname to check
     * @return  string  real classname
     */
    function get_real_class($class)
    {
        static $classes = array();

        if(!array_key_exists($class, $classes)) {
            $reflect = new ReflectionClass($class);
            $classes[$class] = $reflect->getName();
        }

        return $classes[$class];
    }

}

if(!function_exists('array_sort_column')) {

    /**
     * Sorts an array of associative arrays or objects by column
     * @param string $column Column name
     * @param array $arr
     * @param int $sorting
     * @param boolean $comparefn strnatcasecmp by default (case insensitive), for case sensitive use 'strnatcmp'
     * @return boolean 
     */
    function array_sort_column($column, $arr, $sorting = SORT_ASC, $comparefn = 'strnatcasecmp')
    {
        if(is_array($arr) && (count($arr) > 0)) {
            usort($arr, function($a, $b) use ($comparefn, $column, $sorting) {
                $a = is_array($a) ? ((object) $a) : $a;
                $b = is_array($b) ? ((object) $b) : $b;

                if(isset($a->$column) && isset($b->$column)) {
                    if($sorting == SORT_ASC) {
                        return call_user_func($comparefn, $a->$column, $b->$column);
                    } else {
                        return call_user_func($comparefn, $b->$column, $a->$column);
                    }
                } else {
                    return 0;
                }
            });
        }
        return $arr;
    }

}

if(!function_exists('array_safe_value')) {

    /**
     * Checks if a variable exists inside an array and matches the given php filter or regular expression.
     * If it matches returns the variable value, otherwise returns $default
     * 
     * @param array $arr Associated array of values
     * @param string $key Array key name
     * @param mixed $default Default value if the variable is not set or regexp is false
     * @param mixed $validation FILTER_* constant value, regular expression or callable method/function (that returns a boolean i.e. is_string)
     * @return mixed The variable value
     */
    function array_safe_value(array $arr, $key, $default = null, $validation = null)
    {
        if($validation === true) {
            // has
            return isset($arr[$key]);
        }
        if(isset($arr[$key])) {
            $value = $arr[$key];
            if($validation != null) {
                if(is_string($validation) && ($validation{0} == '/')) {
                    //regexp
                    return (preg_match($validation, $value) > 0) ? $value : $default;
                } elseif(is_int($validation)) {
                    // FILTER_* constant
                    return filter_var($value, $validation) ? $value : $default;
                } elseif(is_callable($validation)) {
                    // validation function
                    return $validation($value) ? $value : $default;
                } else {
                    // exact equal comparison
                    return ($validation === $value) ? $value : $default;
                }
            } else {
                return empty($value) ? $default : $value;
            }
        } else {
            return $default;
        }
    }

}

if(!function_exists('array_sanitize')) {

    function array_sanitize(array $arr, $removeHtml = true, $replacement = ' ', $replaceChars = array('>', '<', '\\', '/', '"', '\''), $trimChars = ' .,-_')
    {
        if($removeHtml) {
            foreach($arr as $k => $v) {
                $arr[$k] = trim(str_replace($replaceChars, $replacement, strip_tags($v)), $trimChars);
            }
        } else {
            foreach($arr as $k => $v) {
                $arr[$k] = trim(str_replace($replaceChars, $replacement, $v), $trimChars);
            }
        }
        return $arr;
    }

}

if(!function_exists('array_key_prefix')) {

    function array_key_prefix(array $arr, $prefix)
    {
        $newArr = array();
        foreach($arr as $k => $v) {
            $newArr[$prefix . $k] = $v;
        }
        return $newArr;
    }

}

if(!function_exists('array_key_suffix')) {

    function array_key_suffix(array $arr, $suffix)
    {
        $newArr = array();
        foreach($arr as $k => $v) {
            $newArr[$k . $suffix] = $v;
        }
        return $newArr;
    }

}

if(!function_exists('array_validate')) {

    /**
     * 
     * @param array $arr
     * @param array $validations key value pairs of array key and validation (e=not empty, callable, filter constant, regex)
     * @return array|true The keys that produced an error or true if success
     */
    function array_validate(array $arr, array $validations)
    {
        $errors = array();

        foreach($validations as $field => $validation) {
            if(empty($validation)) {
                continue;
            }
            if(!isset($arr[$field])) {
                $errors[] = $field;
                continue;
            }
            if(($validation == 'e') and empty($arr[$field])) {
                $errors[] = $field;
                continue;
            }
            if(is_callable($validation) and ( call_user_func($arr[$field]) == false)) {
                $errors[] = $field;
                continue;
            }
            if(is_numeric($validation) and ( !filter_var($arr[$field], $validation))) {
                $errors[] = $field;
                continue;
            }

            if(is_string($validation) and ( $validation{0} == '/') and ( preg_match($validation, $arr[$field]) == false)) {
                $errors[] = $field;
                continue;
            }
        }

        if(empty($errors)) {
            return true;
        }
        return $errors;
    }

}

if(!function_exists('base64_encode_safe')) {

    /**
     * Base64 encode (Binary to ASCII or btoa in javascript)
     * @param string $data
     * @param bool $url_safe
     * @return string The base64 ASCII string
     */
    function base64_encode_safe($data, $url_safe = false)
    {
        $data = base64_encode($data);
        if($url_safe) {
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        }
        return $data;
    }

}

if(!function_exists('base64_decode_safe')) {

    /**
     * Base64 decode (ASCII to binary or atob in javascript)
     * @param string $data String encoded using str::base64encode()
     * @param bool $url_safe
     * @return string The binary string
     */
    function base64_decode_safe($data, $url_safe = false)
    {
        if($url_safe) {
            $data = str_replace(array('-', '_'), array('+', '/'), $data);
            $mod4 = strlen($data) % 4;
            if($mod4) {
                $data .= substr('====', $mod4);
            }
        }
        return base64_decode($data);
    }

}

if(!function_exists('str_is_regex')) {

    function str_is_regex($str)
    {
        return is_string($str) and ( (preg_match('/^\/.*\/[imsxeADSUXJu]*$/', $str)) > 0);
    }

}

if(!function_exists('str_is_json')) {

    function str_is_json($str)
    {
        return is_string($str) and is_object(@json_decode($str, false, 1));
    }

}

if(!function_exists('str_is_html')) {

    function str_is_html($str)
    {
        return is_string($str) and ( preg_match('/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)\/?>/i', $str) > 0);
    }

}
