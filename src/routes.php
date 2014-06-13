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
    Route::get('login', array('as' => 'backend.login', 'uses' => $controller . '@login'));
    Route::post('login', $controller . '@do_login');
    Route::get('logout', array('as' => 'backend.logout', 'uses' => $controller . '@logout'));
    Route::get('auth/forgot', array('as' => 'backend.forgot_password', 'uses' => $controller . '@forgot_password'));
    Route::post('auth/forgot', $controller . 'do_forgot_password');
    Route::get('auth/reset/{token}', array('as' => 'backend.reset_password', 'uses' => $controller . '@reset_password'));
    Route::post('auth/reset', $controller . 'do_reset_password');
});

// Backend routes with auth
Route::langGroup(array('prefix' => $backend_basepath, 'before' => 'auth.backend'), function() {
    // Backend home

    Sentinel::guard('*/' . Backend::config('basepath') . '/generator', [], [], 'generate_code', true);
    Route::any('/', array('as' => 'backend.home', 'uses' => 'Thor\Backend\MainController@index'));
    Route::get('/generator/', array('as' => 'backend.generator', 'uses' => 'Thor\Backend\GeneratorController@create'));
    Route::post('/generator/', 'Thor\Backend\GeneratorController@generate');
});

// Base modules
Backend::resourceRoutes('permission', true, null, Thor::modelClass('permission'));
Backend::resourceRoutes('role', true, null, Thor::modelClass('role'));
Backend::resourceRoutes('user', true, null, Thor::modelClass('user'));
Backend::resourceRoutes('language', true, null, Thor::modelClass('language'));

// Site 404
//App::missing(function($e) {
//    $pref = (Backend::inside() ? 'thor::backend.' : '');
//    return Response::view($pref.'errors.404', array('exception' => $e), 404);
//});

App::error(function($exception, $code) {
    $pref = ((Backend::inside() && Backend::hasAccess()) ? 'thor::backend.' : '');
    switch ($code) {
        case 403:
            return Response::view($pref . 'errors.403', array('exception' => $exception), 403);

        case 404:
            return Response::view($pref . 'errors.404', array('exception' => $exception), 404);

        default:
            return Response::view($pref . 'errors.default', array('exception' => $exception), $code);
    }
});
