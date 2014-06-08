<?php

namespace Thor\Models;

class Page extends Base implements IPageable, ITreeable, IImageable
{

    use TTreeable,
        TPageable,
        TImageable;

    protected $table = 'pages';

}
