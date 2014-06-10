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