<?php

namespace Thor\Support;

class BenchFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.bench';
    }

}
