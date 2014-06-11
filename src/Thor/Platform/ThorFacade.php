<?php

namespace Thor\Platform;

class ThorFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor';
    }

}
