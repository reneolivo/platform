<?php

/*
  |--------------------------------------------------------------------------
  | Backend Filters
  |--------------------------------------------------------------------------
  |
 */

/*
 * Backend user
 */
Route::filter('auth.backend', function() {
    if(!Backend::canBeAccessed()) {
        $ctrl = new Thor\Backend\MainController();
        return $ctrl->login();
    }
});

/*
 * Website user
 */
Route::filter('auth.profile', function() {
    
});


/*
 * Default pageable resolver filter (the route will need a {slug} parameter)
 */
Route::filter('pageable.resolve', function($route) {
    $pageable = Pageable::resolve($route->getParameter('slug'));
    if(($pageable instanceof \Thor\Models\Behaviours\IPageable) == false) {
        App::abort(404);
    }
});
