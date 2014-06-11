<?php

namespace Thor\Platform;

class SentinelFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.sentinel';
    }

}
