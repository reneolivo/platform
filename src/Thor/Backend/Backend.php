<?php

namespace Thor\Backend;

use Str;

class Backend
{

    /**
     * Laravel application
     * 
     * @var \Illuminate\Foundation\Application
     */
    protected $app;
    protected $installed = null;

    /**
     * Creates a new Backend instance.
     * 
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Generate a multilingual URL to the given backend path
     *
     * @param string  $path
     * @param mixed  $extra
     * @param bool  $secure
     * @param string  $langCode If null, the current Lang::code() will be used
     * @return string
     * @static 
     */
    public function url($path = '/', $extra = array(), $secure = null, $langCode = null)
    {
        return $this->app['thor.url']->langTo($this->config('basepath') . '/' . ltrim($path, '/'), $extra, $secure, $langCode);
    }

    public function asset($path, $secure = null)
    {
        return asset('packages/thor/platform/' . ltrim($path, '/'), $secure);
    }

    public function config($key, $default = null)
    {
        return $this->app['config']->get('thor::backend.' . $key, $default);
    }

    public function requestIs($plural = '', $after = '.*')
    {
        if (!empty($plural)) {
            $plural.='\/?';
        }
        $base = trim($this->config('basepath'), '/');
        if ($this->app['thor']->config('i18n.enabled')) {
            $expr = '/^' . ($this->app['thor.lang']->code() . '\/' . $base . '\/' . trim($plural, '/'));
        } else {
            $expr = '/^' . ($base . '\/' . trim($plural, '/'));
        }
        $path = trim($this->app['request']->path(), '/');
        return (preg_match($expr . $after . '$/', $path) != false);
    }

    /**
     * 
     * @return bool True if the current request is a backend request
     */
    public function inside()
    {
        return $this->requestIs('');
    }

    /**
     * Defines routes for a given resource name
     * @param string $singular
     * @param boolean $guard If true, routes will be guarded applying SentinelFacade::resourceFilters
     * @param type $controllerClass
     * @param type $modelClass
     * @param mixed $onFail Value that will be returned in the filter.
     * If it's a callable it will be executed. If it's false, the filter will abort
     * with code 403 on fail.
     */
    public function resourceRoutes($singular, $guard = false, $controllerClass = null, $modelClass = null, $onFail = null)
    {
        $this->app['thor.router']->langGroup(array('prefix' => $this->app['config']->get('thor::backend.basepath')
            , 'before' => 'auth.backend'), function()
                use($singular, $guard, $controllerClass, $modelClass, $onFail) {

            $plural = Str::plural($singular);
            $rt = 'backend.' . $plural;
            $c = ($controllerClass ? $controllerClass : ('\\Thor\\Backend\\' . ucfirst(Str::camel($plural)) . 'Controller'));
            $model = ($modelClass ? $modelClass : ('\\Thor\\Models\\' . ucfirst(Str::camel($singular))));

            $this->app['thor.router']->model($singular, $model);
            $this->app['thor.router']->pattern($singular, '[0-9]+');

            if ($guard) {
                $this->app['thor.sentinel']->resourceFilters($singular, $onFail);
            }

            // index
            $this->app['thor.router']->get($plural, array('as' => $rt . '.index'
                , 'uses' => $c . '@index', 'before' => 'sentinel.list_' . $plural));

            // create
            $this->app['thor.router']->get($plural . '/create'
                    , array('as' => $rt . '.create', 'uses' => $c . '@create', 'before' => 'sentinel.create_' . $plural));

            // store
            $this->app['thor.router']->post($plural, array('as' => $rt . '.store'
                , 'uses' => $c . '@store', 'before' => 'sentinel.create_' . $plural));

            // show
            $this->app['thor.router']->get($plural . '/{' . $singular . '}/show'
                    , array('as' => $rt . '.show', 'uses' => $c . '@show', 'before' => 'sentinel.read_' . $plural));

            // edit
            $this->app['thor.router']->get($plural . '/{' . $singular . '}'
                    , array('as' => $rt . '.edit', 'uses' => $c . '@edit', 'before' => 'sentinel.update_' . $plural));

            // update
            $this->app['thor.router']->patch($plural . '/{' . $singular . '}'
                    , array('as' => $rt . '.update', 'uses' => $c . '@update', 'before' => 'sentinel.update_' . $plural));

            // destroy
            $this->app['thor.router']->delete($plural . '/{' . $singular . '}'
                    , array('as' => $rt . '.destroy', 'uses' => $c . '@destroy', 'before' => 'sentinel.delete_' . $plural));
        });
    }

}
