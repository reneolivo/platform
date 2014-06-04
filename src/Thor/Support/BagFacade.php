<?php

namespace Thor\Support;

class BagFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.bag';
    }

}
