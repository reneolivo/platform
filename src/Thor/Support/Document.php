<?php

namespace Thor\Support;

/**
 * Helper for HTML common variables
 */
class Document extends Object
{

    public function __construct(array $properties = array())
    {

        $properties = array_merge([
            'charset' => 'utf-8',
            'lang' => 'en',
            'classes' => [],
            'viewport' => 'width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1',
            'title' => '',
            'description' => '',
            'keywords' => false,
            'robots' => 'INDEX,FOLLOW',
            'generator' => false,
            'author' => false,
            'canonical' => false,
            'alternateLangs' => false,
            'favicon' => false,
            'pageable' => false,
            'name' => '',
            'view' => '',
            'id' => false,
            'error' => false
                ], $properties);
        parent::__construct($properties);
    }

    protected function getOrSet($prop, $set, $value)
    {
        if ($set === true) {
            $this->$prop = $value;
            return $this;
        }
        return isset($this->$prop) ? $this->$prop : null;
    }

    /**
     * Current pageable ID, if any
     * @param string $value
     * @return mixed
     */
    public function id()
    {
        return $this->pageable() ? $this->pageable()->id : false;
    }

    /**
     * Is this document an error page?
     * @param int $value
     * @return int|static
     */
    public function error($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function name($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function charset($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function lang($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param array $value
     * @return array|static
     */
    public function classes(array $value = null)
    {
        if (func_num_args() > 0) {
            $this->classes = $value;
        }
        return $this->classes;
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function viewport($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function title($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function description($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function keywords($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function robots($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function generator($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function author($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function canonical($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param array $value
     * @return string|static
     */
    public function alternateLangs(array $value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return string|static
     */
    public function favicon($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * 
     * @param string $value
     * @return \Thor\Models\IPageable|static
     */
    public function pageable(\Thor\Models\IPageable $value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    /**
     * Current document view name
     * @param string $value
     * @return string|static
     */
    public function view($value = null)
    {
        return $this->getOrSet(__FUNCTION__, (func_num_args() > 0), $value);
    }

    public function __call($name, $arguments)
    {
        $set = (count($arguments) > 0);
        return $this->getOrSet($name, $set, ($set ? $arguments[0] : null));
    }

    public function addClass($classname)
    {
        $this->props['classes'][] = $classname;
        $this->props['classes'] = array_unique($this->props['classes']);
    }

    public function hasClass($classname)
    {
        return in_array($classname, $this->props['classes']);
    }

    public function removeClass($classname)
    {
        if (($key = array_search($classname, $this->props['classes'])) !== false) {
            unset($this->props['classes'][$key]);
        }
    }

}
