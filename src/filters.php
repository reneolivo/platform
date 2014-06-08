<?php

/*
  |--------------------------------------------------------------------------
  | Admin Filters
  |--------------------------------------------------------------------------
  |
 */

/*
 * Admin user
 */
Route::filter('auth.admin', function() {
    if(!Admin::isAuthenticated()){
        $ctrl = new Thor\Admin\MainController();
        return $ctrl->login();
    }
});

/*
 * Website user
 */
Route::filter('auth.profile', function() {
    
});
