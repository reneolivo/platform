<?php

namespace Thor\Support;

class DocumentFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.document';
    }

}
