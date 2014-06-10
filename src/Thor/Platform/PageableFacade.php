<?php

namespace Thor\Platform;

class PageableFacade extends \Illuminate\Support\Facades\Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thor.pageable.manager';
    }

}
