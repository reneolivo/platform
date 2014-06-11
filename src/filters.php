<?php

// Always expose the current view name and slug
View::composer('*', function($view) {
    Thor\Support\DocumentFacade::view($view->getName());
    Thor\Support\DocumentFacade::view_slug(Str::slug(str_replace(array('.', '::'), '-', $view->getName()), '-'));
    View::share(array_key_prefix(Thor\Support\DocumentFacade::toArray(), 'doc_'));
});


/*
 * Backend user
 */
Route::filter('auth.backend', function() {
    if (!Thor\Platform\SentinelFacade::hasBackendAccess()) {
        return Redirect::route('backend.login');
    }
});

/*
 * Frontend user
 */
Route::filter('auth.frontend', function() {
    
});
