<?php

namespace Thor\Backend;

use View,
    Redirect,
    Input,
    \Thor\Platform\ThorFacade;

/*
  |--------------------------------------------------------------------------
  | \Thor\Models\User backend controller
  |--------------------------------------------------------------------------
  |
  | This is a default Thor CMS backend controller template for resource management.
  | Feel free to change it to your needs.
  |
 */

class UsersController extends Controller
{

    /**
     * Repository
     *
     * @var \Thor\Models\User     */
    protected $user;

    public function __construct(\Thor\Models\User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->user->all();

        return View::make('thor::backend.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('thor::backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->user->username = Input::get('username');
        $this->user->email = Input::get('email');
        $this->user->password = Input::get('password');

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $this->user->password_confirmation = Input::get('password_confirmation');

        // Save if valid. Password field will be hashed before save
        $this->user->save();

        if ($this->user->id) {

            return Redirect::route('backend.users.index');
        }

        return Redirect::route('backend.users.create')
                        ->withInput()
                        ->withErrors($this->user->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\User  $user 
     * @return Response
     */
    public function show(\Thor\Models\User $user)
    {

        return View::make('thor::backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\User  $user 
     * @return Response
     */
    public function edit(\Thor\Models\User $user)
    {

        if (is_null($user)) {
            return Redirect::route('backend.users.index');
        }

        $roles = ThorFacade::model('role')->all();
        $user_roles = $user->roles()->get()->lists('id');

        return View::make('thor::backend.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\User  $user 
     * @return Response
     */
    public function update(\Thor\Models\User $user)
    {
        $user->username = Input::get('username');
        $user->email = Input::get('email');
        if (strlen(Input::get('password')) > 0) {
            $user->password = Input::get('password');

            // The password confirmation will be removed from model
            // before saving. This field will be used in Ardent's
            // auto validation.
            $user->password_confirmation = Input::get('password_confirmation');
        }

        // Save if valid. Password field will be hashed before save
        if ($user->save()) {
            if (\Sentinel::can('update_roles')) {
                $user->roles()->sync(\Input::get('roles', array()));
            }
            return Redirect::route('backend.users.edit', $user->id);
        }

        return Redirect::route('backend.users.edit', $user->id)
                        ->withInput()
                        ->withErrors($user->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\User  $user 
     * @return Response
     */
    public function destroy(\Thor\Models\User $user)
    {
        $user->delete();

        return Redirect::route('backend.users.index');
    }

}
