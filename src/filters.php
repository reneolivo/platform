<?php

// Always expose the current view name and slug
View::composer('*', function($view) {
    View::share('current_view', $view->getName());
    View::share('current_view_slug', Str::slug(str_replace(array('.', '::'), '-', $view->getName()), '-'));
});
