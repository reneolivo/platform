<?php

namespace Thor\Models;

class Page extends Base implements IPageable, ITreeable, IImageable
{

    use TTranslatable,
        TTreeable,
        TPageable,
        TImageable;

    protected $table = 'pages';

}
