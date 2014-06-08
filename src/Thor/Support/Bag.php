<?php

namespace Thor\Support;

/**
 * Simple variable container with locker and flag features
 */
class Bag extends Object
{

    /**
     *
     * @var array
     */
    protected $locked = array();

    /**
     *
     * @var Object
     */
    protected $flags;

    public function __construct(array $properties = array())
    {
        parent::__construct($properties);
        $this->flags = new Object();
    }

    public function get($key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }
        return $this->$key;
    }

    public function set($key, $value)
    {
        if ($this->locked($key)) {
            $this->throwLockedException($key);
        } else {
            $this->$key = $value;
        }
    }

    public function remove($key)
    {
        if (isset($this->data[$key])) {
            if ($this->locked($key)) {
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

    public function locked($key)
    {
        return isset($this->locked[$key]) && ($this->locked[$key] === true);
    }

    public function unlock($key)
    {
        if (isset($this->locked[$key])) {
            unset($this->locked[$key]);
            return true;
        }
        return false;
    }

    public function flag($key)
    {
        $this->flags->set($key, true);
    }

    public function flagged($key)
    {
        return $this->flags->has($key) && ($this->flags->$key === true);
    }

    public function unflag($key)
    {
        if ($this->flags->has($key)) {
            unset($this->flags->$key);
        }
    }

    public function hasFlags()
    {
        return ($this->flags->count() > 0);
    }

    public function getFlags()
    {
        return array_keys($this->flags->toArray());
    }

    protected function throwLockedException($key)
    {
        throw new \Exception('Variable ' . $key . ' is locked and cannot be replaced or removed.');
    }

}
