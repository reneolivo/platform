<?php

namespace Thor\Backend;

use View;

class MainController extends Controller
{

    public function index()
    {
        return View::make('thor::backend.index', array('page' => 'dashboard'));
    }

    public function login()
    {
        return View::make('thor::backend.login', array('page' => 'login', 'unwrap'=>true));
    }

}
