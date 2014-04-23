<?php

Route::filter('admin-auth', function() {
    if (Auth::guest() || (auth_user()->can('access_backend') == false)){
        return Redirect::route('account.login');
    }
});