<?php

namespace Thor;

use Illuminate\Support\ServiceProvider;

class ThorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('thor/framework', 'thor');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBag();
        $this->registerDocument();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('thor.bag', 'thor.document');
    }

    /**
     *
     * @return void
     */
    protected function registerBag()
    {
        $this->app->bindShared('thor.bag', function($app) {
            return new Support\Bag($app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerDocument()
    {
        $this->app->bindShared('thor.document', function($app) {
            return new Support\Document($app);
        });
    }

}
