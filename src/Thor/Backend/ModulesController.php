<?php
namespace Thor\Backend;

use View,
    Redirect,
    Validator,
    Form, CRUD, Artisan;
/*
|--------------------------------------------------------------------------
| \Thor\Models\Module backend controller
|--------------------------------------------------------------------------
|
| This is a default Thor CMS backend controller template for resource management.
| Feel free to change it to your needs.
|
*/
class ModulesController extends Controller {

    /**
     * Repository
     *
     * @var \Thor\Models\Module     */
    protected $module;

    public function __construct(\Thor\Models\Module $module) {
        $this->module = $module;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $modules = $this->module->all();

        return View::make('thor::backend.modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('thor::backend.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Form::allInput();
        if(isset($input['name'])){
            $input['name'] = \Str::singular(strtolower($input['name']));
        }
        if ($this->module->validate($input)) {
            $module = $this->module->create($input);
            
            CRUD::createResourceFiles($module->singular(), [], [], $module->is_pageable, $module->is_imageable);
            Artisan::call('migrate');
            CRUD::createPermissions($module->singular(), true);
            
            if($module->is_active){
                return Redirect::to(\Backend::url($module->plural()));
            }else{
                return Redirect::route('backend.modules.index');
            }
        }

        return Redirect::route('backend.modules.create')
                        ->withInput()
                        ->withErrors($this->module->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\Module  $module 
     * @return Response
     */
    public function show(\Thor\Models\Module $module) {

        return View::make('thor::backend.modules.show', compact('module'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Module  $module 
     * @return Response
     */
    public function edit(\Thor\Models\Module $module) {

        if (is_null($module)) {
            return Redirect::route('backend.modules.index');
        }
        $permissions = $module->permissions()->get();

        return View::make('thor::backend.modules.edit', compact('module', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Module  $module 
     * @return Response
     */
    public function do_edit(\Thor\Models\Module $module) {
        $input = array_merge(array( // for unchecked checkboxes:
            'is_pageable'=>false,
            'is_translatable'=>false,
            'is_imageable'=>false,
            'is_active'=>false,
        ),
                Form::allInput());
        if(isset($input['name'])){
            $input['name'] = strtolower($input['name']);
        }

        if ($module->validate($input)) {
            $module->update($input);

            return Redirect::route('backend.modules.edit', $module->id);
        }
        
        return Redirect::route('backend.modules.edit', $module->id)
                        ->withInput()
                        ->withErrors($module->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\Module  $module 
     * @return Response
     */
    public function do_delete(\Thor\Models\Module $module) {
        $module->delete();

        return Redirect::route('backend.modules.index');
    }

}
