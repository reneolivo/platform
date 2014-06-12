<?php

// Backend basic routes

$backend_basepath = Backend::config('basepath');

// Redirect to a lang
Route::any('/' . $backend_basepath . '/', array('as' => 'backend.root', 'uses' => function() {
        return Redirect::to(Backend::url(), 302);
    }
));

// Backend routes that doesn't need login
Route::langGroup(array('prefix' => $backend_basepath), function() {
    $controller = 'Thor\Backend\AuthController';
    Route::get('login', array('as' => 'backend.login', 'uses' => $controller.'@login'));
    Route::post('login', $controller.'@do_login');
    Route::get('logout', array('as' => 'backend.logout', 'uses' => $controller.'@logout'));
    Route::get('auth/forgot', array('as' => 'backend.forgot_password', 'uses' => $controller.'@forgot_password'));
    Route::post('auth/forgot', $controller.'do_forgot_password');
    Route::get('auth/reset/{token}', array('as' => 'backend.reset_password', 'uses' => $controller.'@reset_password'));
    Route::post('auth/reset', $controller.'do_reset_password');
});

// Backend routes with auth
Route::langGroup(array('prefix' => $backend_basepath, 'before' => 'auth.backend'), function() {
    // Backend home

    Sentinel::guard('*/'.Backend::config('basepath').'/generator', [], [], 'generate_code', true);
    Route::any('/', array('as' => 'backend.home', 'uses' => 'Thor\Backend\MainController@index'));
    Route::get('/generator/', array('as' => 'backend.generator', 'uses' => 'Thor\Backend\GeneratorController@create'));
    Route::post('/generator/', 'Thor\Backend\GeneratorController@generate');
});

// Base modules
Backend::resourceRoutes('permission', false);
Backend::resourceRoutes('role', true);
Backend::resourceRoutes('user', true);
Backend::resourceRoutes('language', true);

// Site 404
App::missing(function($e) {
    if (Backend::inside()) {
        return Response::view('thor::backend.error', array('page' => 'error'), 404);
    } else {
        Thor\Support\DocumentFacade::title('Error 404')->h1('Error 404')->content('Page Not Found')->error(true);
        return Response::view('404', array(), 404);
    }
});
