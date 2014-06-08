<?php

namespace Thor\Admin;

use Illuminate\Support\ServiceProvider,
    View;

class AdminServiceProvider extends ServiceProvider
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
        $this->package('thor/platform', 'admin');
        $package_src = realpath(__DIR__ . '/../../');

        View::prependNamespace('admin', app_path() . '/views/admin'); // support view overwrite for admin

        include_once $package_src . '/filters.php';
        include_once $package_src . '/routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('thor.backend', function($app) {
            return new \Thor\Backend\Backend($app);
        });

        $this->app['command.thor.install'] = $this->app->share(function($app) {
            return new InstallCommand($app);
        });

        $this->commands(
                'command.thor.install'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('thor.backend');
    }

}
