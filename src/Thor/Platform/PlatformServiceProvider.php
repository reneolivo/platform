<?php

namespace Thor\Platform;

use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider,
    View,
    Backend,
    Pageable;

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

        $app = $this->app;

        // Always expose the current view name and all the Document vars
        View::composer('*', function($view) use($app) {
            $app['thor.document']->view($view->getName());
            $app['thor.document']->view_slug(\Str::slug(str_replace(array('.', '::'), '-', $view->getName()), '-'));
            View::share(array_key_prefix($app['thor.document']->toArray(), 'doc_'));
        });

        include_once $package_src . '/filters.php';

        $backend_routes_file = app_path('routes/backend.php');
        if (file_exists($backend_routes_file)) {
            include_once $backend_routes_file;
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // IoC
        $this->registerTranslator();
        $this->registerRouter();
        $this->registerUrlGenerator();
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        //
        $this->registerBag();
        $this->registerBench();
        $this->registerDocument();
        $this->registerBackend();
        $this->registerCrudBuilder();
        $this->registerLangPublisher();

        // Commands
        $this->registerThorLangPublishCommand();
        $this->registerThorInstallCommand();
        $this->registerThorGenerateCommand();
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
            'thor.html',
            'thor.form',
            'thor.bag',
            'thor.bench',
            'thor.document',
            'thor.backend',
            'thor.crud',
            'thor.lang.publisher',
            'command.thor.lang.publish',
            'command.thor.install',
            'command.thor.generate'
        );
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
            return new \Thor\Platform\Router($app['events'], $app);
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
    protected function registerBag()
    {
        $this->app->bindShared('thor.bag', function($app) {
            return new \Thor\Support\Bag();
        });
    }

    /**
     *
     * @return void
     */
    protected function registerBench()
    {
        $this->app->bindShared('thor.bench', function($app) {
            return new \Thor\Support\Bench();
        });
    }

    /**
     *
     * @return void
     */
    protected function registerDocument()
    {
        $this->app->bindShared('thor.document', function($app) {
            return new \Thor\Support\Document();
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

    /**
     *
     * @return void
     */
    protected function registerCrudBuilder()
    {
        $this->app->bindShared('thor.crud', function($app) {
            return new \Thor\Generators\CrudBuilder($app);
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

        $this->app->bindShared('command.thor.lang.publish', function($app) {
            return new \Thor\I18n\LangPublishCommand($app['thor.lang.publisher']);
        });

        $this->commands(array(
            'command.thor.lang.publish'
        ));
    }

    protected function registerThorInstallCommand()
    {

        $this->app->bindShared('command.thor.install', function($app) {
            return new InstallCommand($app);
        });

        $this->commands(
                'command.thor.install'
        );
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerThorGenerateCommand()
    {

        $this->app->bindShared('command.thor.generate', function($app) {
            return new \Thor\Generators\GenerateCommand($app);
        });

        $this->commands(
                'command.thor.generate'
        );
    }

}
