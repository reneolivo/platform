<?php

namespace Thor\Generators;

/**
 * @see \Illuminate\Html\HtmlBuilder
 */
class CrudFacade extends \Illuminate\Support\Facades\Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thor.crud';
    }

}
