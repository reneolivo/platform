<?php

namespace Thor\Backend;

use View,
    Redirect;

/*
  |--------------------------------------------------------------------------
  | \Thor\Models\Module backend controller
  |--------------------------------------------------------------------------
  |
  | This is a default Thor CMS backend controller template for resource management.
  | Feel free to change it to your needs.
  |
 */

class GeneratorController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('thor::backend.generator');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function generate()
    {
        /*
        $this->module = CRUD::createModule(\Input::all(), \Input::get('behaviours')
                        , \Input::get('general_fields'), \Input::get('translatable_fields')
                        , \Input::get('listable_fields'));

        if ((!$this->module->hasErrors()) and $this->module->exists()) {
            if ($this->module->is_active) {
                return Redirect::to($this->module->url());
            } else {
                return Redirect::route('backend.generator');
            }
        }
        */
        return Redirect::route('backend.generator')
                        ->withInput()
                        ->withErrors(['Code needs reimplementation'])
                        ->with('message', 'There were validation errors.');
    }

}
