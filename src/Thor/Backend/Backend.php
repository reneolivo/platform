<?php

namespace Thor\Backend;

use URL,
    Config,
    Entrust,
    Request,
    Route,
    Lang;

class Backend
{
    
    /**
     * Laravel application
     * 
     * @var \Illuminate\Foundation\Application
     */
    protected $app;
    protected $installed = null;
    protected $modules = null;

    /**
     * Creates a new Backend instance.
     * 
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->app = $app;

        if ($this->canBeAccessed()) {
            $this->app['thor.document']->addClass('is-backend');
        }
    }
    
    public function getAccessPermissionName(){
        return 'access_backend';
    }

    /**
     * 
     * @return \Thor\Models\Module[]|\Illuminate\Database\Eloquent\Collection
     */
    public function modules()
    {
        if ($this->modules === null) {
            if ($this->isInstalled()) {
                $this->modules = \Thor\Models\Module::active()->sorted()->get();
            } else {
                return array();
            }
        }
        return $this->modules;
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
        return URL::langTo($this->config('basepath') . '/' . ltrim($path, '/'), $extra, $secure, $langCode);
    }

    public function asset($path, $secure = null)
    {
        return asset('packages/thor/platform/' . ltrim($path, '/'), $secure);
    }

    public function config($key, $default = null)
    {
        return Config::get('thor::backend.' . $key, $default);
    }

    public function canBeAccessed()
    {
        return Entrust::can($this->getAccessPermissionName());
    }

    public function isBackendRequest()
    {
        $base = $this->config('basepath');
        return (Request::is(Lang::code() . '/' . $base . '/*') or Request::is($base . '/*'));
    }

    public function isInstalled()
    {
        if ($this->installed === null) {
            $this->installed = (\Schema::hasTable('users') === true);
        }
        return $this->installed;
    }

}
