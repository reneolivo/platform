<?php

// Backend basic routes

$backend_base_route = Backend::config('base_route');

// Redirect to a lang
Route::any('/' . $backend_base_route . '/', array('as' => 'backend.root', 'uses' => function() {
return Redirect::to(Backend::url(), 302);
}
));

// Backend routes that doesn't need auth
Route::langGroup(array('prefix' => $backend_base_route), function() {
    // Backend Confide routes (that doesn't need auth)
    Route::get('login', array('as' => 'backend.login', 'uses' => '\\Thor\\Backend\\AuthController@login'));
    Route::post('login', '\\Thor\\Backend\\AuthController@do_login');
    Route::get('auth/confirm/{code}', '\\Thor\\Backend\\AuthController@confirm');
    Route::get('auth/forgot_password', array('as' => 'backend.forgot_password', 'uses' => '\\Thor\\Backend\\AuthController@forgot_password'));
    Route::post('auth/forgot_password', '\\Thor\\Backend\\AuthController@do_forgot_password');
    Route::get('auth/reset_password/{token}', '\\Thor\\Backend\\AuthController@reset_password');
    Route::post('auth/reset_password', '\\Thor\\Backend\\AuthController@do_reset_password');
});

// Backend routes with auth
Route::langGroup(array('prefix' => $backend_base_route, 'before' => 'auth.backend'), function() {
    // Backend home
    Route::any('/', array('as' => 'backend.home', 'uses' => '\\Thor\\Backend\\MainController@index'));

    // Backend Confide routes
    Route::get('logout', array('as' => 'backend.logout', 'uses' => '\\Thor\\Backend\\AuthController@logout'));
});

// Base modules
CRUD::createResourceRoutes('permission', true);
CRUD::createResourceRoutes('role', true);
CRUD::createResourceRoutes('user', true);
CRUD::createResourceRoutes('language', true);
CRUD::createResourceRoutes('module', true);

// Registered modules
foreach (Backend::modules() as $module) {
    CRUD::createResourceRoutes($module->name, true);
}

// Site 404
App::missing(function($e) {
    if (Backend::isBackendRequest()) {
        return Response::view('thor::backend.error', array('page' => 'error'), 404);
    } else {
        Doc::title('Error 404')->h1('Error 404')->content('Page Not Found')->error(true);
        return Response::view('404', array(), 404);
    }
});
