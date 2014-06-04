<?php

namespace Thor\Support;

use Session;

/**
 * Laravel session adapter for using in $_SESSION global:
 * 
 * $_SESSION = new SessionAdapter();
 */
class SessionAdapter implements \ArrayAccess
{

    /**
     * Native PHP session backup
     * @var array 
     */
    protected $backup = array();

    public function __construct()
    {
        if(isset($_SESSION)) {
            $this->backup = $_SESSION;
        }
    }

    public function getNativeSession()
    {
        return $this->backup;
    }

    public function offsetExists($offset)
    {
        return Session::has($offset);
    }

    public function offsetGet($offset)
    {
        return Session::get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return Session::put($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return Session::forget($offset);
    }

}
