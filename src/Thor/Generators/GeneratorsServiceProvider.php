<?php

namespace Thor\Generators;

use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    // protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('thor/generators');
        Facades\Form::swap($this->app['thor.form']);
        Facades\HTML::swap($this->app['thor.html']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        $this->registerCrudBuilder();
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'thor.html',
            'thor.form',
            'thor.crud'
        );
    }

}
