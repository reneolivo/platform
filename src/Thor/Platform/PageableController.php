<?php

namespace Thor\Platform;

use Pageable,
    View;

class PageableController extends Controller
{

    /**
     * 
     * @param string $slug Slug route parameter
     * @return mixed
     */
    public function execute($slug = null)
    {
        $content = false;
        if(Pageable::isFound()) {
            $content = Pageable::found()->execute();
        }
        if(empty($content)) {
            App::abort(404);
        }
        return $content;
    }

    public function defaultAction()
    {
        return View::make('default', array(
                    'page' => Pageable::found()
        ));
    }

}
