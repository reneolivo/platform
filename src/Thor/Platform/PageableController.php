<?php

namespace Thor\Platform;

use \Thor\Models\Behaviours\IPageable,
    View,
    Config;

class PageableController extends Controller
{

    /**
     *
     * @var IPageable 
     */
    protected $pageable;

    public function __construct(IPageable $pageable)
    {
        $this->pageable = $pageable;
        $pageable->view ? $pageable->view : Config::get('thor::models.pageable_default_view)');
    }

    public function defaultAction($data = array())
    {
        return $this->make($data);
    }

    protected function make(array $data = array())
    {
        $data['page'] = $this->pageable;
        $viewname = $this->pageable->view;
        if (!View::exists($viewname)) {
            $viewname = Config::get('thor::models.pageable_default_view', 'default');
        }
        return View::make($viewname, $data);
    }

}
