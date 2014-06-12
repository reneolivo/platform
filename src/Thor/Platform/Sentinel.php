<?php

namespace Thor\Platform;

use InvalidArgumentException,
    Str;

class Sentinel
{

    /**
     * Laravel application
     * 
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create a new Sentinel instance.
     * 
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Checks if the current user has a Role by its name
     * 
     * @param string $permission Role name.
     * @return boolean
     */
    public function hasRole($permission)
    {
        if ($this->check()) {
            return $this->user()->hasRole($permission);
        } else {
            return false;
        }
    }

    /**
     * 
     * Check if the current user has the 'backend_access' permission
     *
     * @param string $permission Permission name.
     * @return boolean
     */
    public function hasBackendAccess()
    {
        if ($this->check()) {
            return $this->user()->hasPermission('backend_access');
        } else {
            return false;
        }
    }

    /**
     * 
     * Check if the current user has a permission by its name
     *
     * @param string $permission Permission name.
     * @return boolean
     */
    public function hasPermission($permission)
    {
        if ($this->check()) {
            return $this->user()->hasPermission($permission);
        } else {
            return false;
        }
    }

    /**
     * 
     * Shortcut function for SentinelFacade::hasPermission
     *
     * @param string $permission Permission name.
     * @return boolean
     */
    public function can($permission)
    {
        if ($this->check()) {
            return $this->user()->hasPermission($permission);
        } else {
            return false;
        }
    }

    /**
     * Get the currently authenticated user or null.
     *
     * @return \Illuminate\Auth\UserInterface|\Thor\Models\User|null
     */
    public function user()
    {
        return $this->app['auth']->user();
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     * @static 
     */
    public function check()
    {
        return $this->app['auth']->check();
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->app['auth']->logout();
    }

    /**
     * Protects a route with the given roles and/or permissions by creating and
     * assigning a filter.
     *
     * @param string $pattern  Route pattern
     * @param array|null $methods Constrain HTTP verbs (null = all)
     * @param array|string $roles   The role(s) needed.
     * @param array|string $permissions   The permission needed.
     * @param bool $cumulative Must have all permissions
     * @param mixed $onFail Value that will be returned in the filter.
     * If it's a callable it will be executed. If it's false, the filter will abort
     * with code 403 on fail.
     *
     * @return string The filter name
     * @throws InvalidArgumentException
     */
    public function guard($pattern, $methods = null, $roles = null, $permissions = null, $cumulative = true, $onFail = false)
    {
        if (is_string($permissions)) {
            $permissions = explode(',', $permissions);
        }
        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }
        if (!is_array($permissions) && !is_array($roles)) {
            throw new InvalidArgumentException('You must specify at least one permission or role');
        }

        $filter_name = 'sentinel.' . implode('_', $roles) . '_' . implode('_', $permissions) . '_';
        if (is_array($methods)) {
            $filter_name.=strtolower(implode('_', $methods)) . '_';
        }
        $filter_name.=Str::slug($pattern, '_');

        $filter = function() use ($roles, $permissions, $cumulative, $onFail) {
            // Check to see if it is false and then
            // check additive flag and that the array only contains false.
            if ($this->user()->ability($roles, $permissions, $cumulative, false)) {
                if (!$onFail) {
                    $this->app->abort(403);
                }

                if ($onFail instanceof \Closure) {
                    return $onFail($roles, $permissions, $cumulative);
                }

                return $onFail;
            }
        };

        // Same as Route::filter, registers a new filter
        $this->app['router']->filter($filter_name, $filter);

        // Same as Route::when, assigns a route pattern to the
        // previously created filter.
        $this->app['router']->when($pattern, $filter_name, $methods);

        return $filter_name;
    }

    /**
     * Defines router filters for LCRUD operations.
     * Checking if current user has permissions
     * 
     * @param string $singular Singular name of the resource
     * @param mixed $onFail Value that will be returned in the filter.
     * If it's a callable it will be executed. If it's false, the filter will abort
     * with code 403 on fail.
     * 
     * @return array The filter names associated to their permission name
     */
    public function resourceFilters($singular, $onFail = null)
    {
        $plural = Str::plural($singular);

        $permissions = array(
            'list',
            'create',
            'read',
            'update',
            'delete'
        );

        $filters = array();

        foreach ($permissions as $perm) {
            $filter = 'sentinel.perm_' . $perm . '_' . $plural;
            $filters[$perm] = $filter;
            $this->app['router']->filter($filter, function() use($perm, $onFail) {
                if ($this->can($perm) === false) {
                    if (!$onFail) {
                        $this->app->abort(403);
                    }

                    if ($onFail instanceof \Closure) {
                        return $onFail($perm);
                    }

                    return $onFail;
                }
            });
        }
        return $filters;
    }

}
