<?php

namespace Thor;

use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider,
    View,
    Doc;

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
        $this->package('thor/framework', 'thor', realpath(__DIR__.'/../'));
        
        Facades\Lang::swap($this->app['thor.translator']);
        Facades\Route::swap($this->app['thor.router']);
        Facades\URL::swap($this->app['thor.url']);

        // Always expose the current view name and all the Document vars
        View::composer('*', function($view) {
            Doc::view($view->getName());
            Doc::view_slug(\Str::slug(str_replace(array('.', '::'), '-', $view->getName()), '-'));
            View::share(array_key_prefix(Doc::toArray(), 'doc_'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslator();
        $this->registerRouter();
        $this->registerUrlGenerator();
        $this->registerPublishCommand();
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
        return array(
            'thor.translator',
            'thor.router',
            'thor.url',
            'thor.lang_publisher',
            'thor.bag',
            'thor.document'
        );
    }

    /**
     *
     * @return void
     */
    protected function registerTranslator()
    {
        $this->app->bindShared('thor.translator', function($app) {
            return new I18n\Translator($app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app->bindShared('thor.router', function($app) {
            return new I18n\Router($app['events'], $app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerUrlGenerator()
    {
        $this->app->bindShared('thor.url', function($app) {
            return new I18n\UrlGenerator($app['thor.router']->getRoutes(), $app->rebinding('request', function ($app, $request) {
                        $app['thor.url']->setRequest($request);
                    }));
        });
    }

    protected function registerPublishCommand()
    {
        $this->app->bindShared('thor.lang_publisher', function($app) {
            return new I18n\LangPublisher($app['files'], $app['path'] . '/lang');
        });

        $this->app->bindShared('command.lang.publish', function($app) {
            return new I18n\LangPublishCommand($app['thor.lang_publisher']);
        });

        $this->commands(array(
            'command.lang.publish'
        ));
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
