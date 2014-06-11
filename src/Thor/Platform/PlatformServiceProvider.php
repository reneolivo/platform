<?php

namespace Thor\Platform;

use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('thor/platform', 'thor');
        $package_src = realpath(__DIR__ . '/../../');
        
        
        //dd(\Thor::model('user'));
        include_once $package_src . '/filters.php';
        
        if(file_exists(app_path('routes/backend.php'))){
            include_once app_path('routes/backend.php');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerThor();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('thor.thor');
    }

    /**
     *
     * @return void
     */
    protected function registerThor()
    {
        $this->app->bindShared('thor.thor', function($app) {
            return new Thor($app);
        });
    }
    protected function registerEntrust()
    {
        $this->app->bind('thor.sentinel', function($app) {
            return new Sentinel($app);
        });
    }

}
