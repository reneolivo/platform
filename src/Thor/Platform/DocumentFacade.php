<?php

namespace Thor\Platform;

class DocumentFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'thor.document';
    }

}
