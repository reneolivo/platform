<?php

namespace Thor\Admin;

use View;

class MainController extends \Controller
{

    public function index()
    {
        return View::make('admin::index', array('page' => 'dashboard'));
    }

    public function login()
    {
        return View::make('admin::login', array('page' => 'login', 'unwrap'=>true));
    }

}
