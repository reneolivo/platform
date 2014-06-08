<?php

namespace Thor\Backend;

class BackendFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.backend';
    }

}
