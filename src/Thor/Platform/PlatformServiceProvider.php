<?php

namespace Thor\Platform;

use Illuminate\Support\Facades;
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

        Facades\Lang::swap($this->app['thor.translator']);
        Facades\Route::swap($this->app['thor.router']);
        Facades\URL::swap($this->app['thor.url']);
        Facades\Form::swap($this->app['thor.form']);
        Facades\HTML::swap($this->app['thor.html']);

        include_once $package_src . '/filters.php';

        if (file_exists(app_path('routes/backend.php'))) {
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
        $this->registerSentinel();
        $this->registerTranslator();
        $this->registerRouter();
        $this->registerUrlGenerator();
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        $this->registerBackend();
        $this->registerLangPublisher();
        // Commands:
        $this->registerThorLangPublishCommand();
        $this->registerThorInstallCommand();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'thor',
            'thor.sentinel',
            'thor.translator',
            'thor.router',
            'thor.url',
            'thor.html',
            'thor.form',
            'thor.backend',
            'thor.lang.publisher',
            // Commands:
            'thor.commands.lang.publish',
            'thor.commands.install',
        );
    }

    /**
     *
     * @return void
     */
    protected function registerThor()
    {
        $this->app->bindShared('thor', function($app) {
            return new Thor($app);
        });
    }

    protected function registerSentinel()
    {
        $this->app->bindShared('thor.sentinel', function($app) {
            return new Sentinel($app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerTranslator()
    {
        $this->app->bindShared('thor.translator', function($app) {
            return new \Thor\I18n\Translator($app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app->bindShared('thor.router', function($app) {
            return new \Thor\I18n\Router($app['events'], $app);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerUrlGenerator()
    {
        $this->app->bindShared('thor.url', function($app) {
            return new \Thor\I18n\UrlGenerator($app['thor.router']->getRoutes(), $app->rebinding('request', function ($app, $request) {
                        $app['thor.url']->setRequest($request);
                    }));
        });
    }

    /**
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->bindShared('thor.html', function($app) {
            return new \Thor\Generators\HtmlBuilder($app['thor.url']);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->bindShared('thor.form', function($app) {
            $form = new \Thor\Generators\FormBuilder($app['thor.html'], $app['thor.url'], $app['session.store']->getToken());
            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     *
     * @return void
     */
    protected function registerBackend()
    {
        $this->app->bindShared('thor.backend', function($app) {
            return new \Thor\Backend\Backend($app);
        });
    }

    protected function registerLangPublisher()
    {
        $this->app->bindShared('thor.lang.publisher', function($app) {
            return new \Thor\I18n\LangPublisher($app['files'], $app['path'] . '/lang');
        });
    }

    protected function registerThorLangPublishCommand()
    {

        $this->app->bindShared('thor.commands.lang.publish', function($app) {
            return new \Thor\I18n\LangPublishCommand($app['thor.lang.publisher']);
        });

        $this->commands(array(
            'thor.commands.lang.publish'
        ));
    }

    protected function registerThorInstallCommand()
    {

        $this->app->bindShared('thor.commands.install', function($app) {
            return new InstallCommand($app);
        });

        $this->commands(
                'thor.commands.install'
        );
    }

}
