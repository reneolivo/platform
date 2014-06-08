<?php
namespace Thor\Backend;

use View,
    Redirect,
    Validator,
    Form;
/*
|--------------------------------------------------------------------------
| \Thor\Models\Role backend controller
|--------------------------------------------------------------------------
|
| This is a default Thor CMS backend controller template for resource management.
| Feel free to change it to your needs.
|
*/
class RolesController extends \Controller {

    /**
     * Repository
     *
     * @var \Thor\Models\Role     */
    protected $role;

    public function __construct(\Thor\Models\Role $role) {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $roles = $this->role->all();

        return View::make('thor::backend.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('thor::backend.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Form::allInput();
        $validation = Validator::make($input, \Thor\Models\Role::$rules);

        if ($validation->passes()) {
            $this->role->create($input);

            return Redirect::route('backend.roles.index');
        }

        return Redirect::route('backend.roles.create')
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\Role  $role 
     * @return Response
     */
    public function show(\Thor\Models\Role $role) {

        return View::make('thor::backend.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Role  $role 
     * @return Response
     */
    public function edit(\Thor\Models\Role $role) {

        if (is_null($role)) {
            return Redirect::route('backend.roles.index');
        }
        
        $permissions = \Permission::all();
        $role_permissions = $role->perms()->get()->lists('id');
        
        
        $users = \User::all();
        $role_users = $role->users()->get()->lists('id');
        return View::make('thor::backend.roles.edit', compact('role', 'permissions', 'role_permissions', 'users', 'role_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Role  $role 
     * @return Response
     */
    public function do_edit(\Thor\Models\Role $role) {
        $input = Form::allInput();
        $validation = Validator::make($input, \Thor\Models\Role::$rules);

        if ($validation->passes()) {
            $role->update($input);
            
            
            if(\Entrust::can('update_permissions')){
                $role->perms()->sync(\Input::get('perms', array()));
            }
            if(\Entrust::can('update_users')){
                $role->users()->sync(\Input::get('users', array()));
            }

            return Redirect::route('backend.roles.edit', $role->id);
        }
        
        return Redirect::route('backend.roles.edit', $role->id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\Role  $role 
     * @return Response
     */
    public function do_delete(\Thor\Models\Role $role) {
        $role->delete();

        return Redirect::route('backend.roles.index');
    }

}
