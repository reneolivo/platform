<?php

namespace Thor\Generators;

use Route,
    Config,
    Entrust,
    Str,
    Redirect,
    View, Backend;

class CrudBuilder
{

    protected function getViewPrefix($isPageable, $transFields)
    {
        return 'thor::generators.';
    }

    protected function getModelClass($singular)
    {
        return Config::get('thor::generators.model_prefix') .
                ucfirst(Str::camel($singular)) .
                Config::get('thor::generators.model_suffix');
    }

    protected function getControllerClass($singular)
    {
        return Config::get('thor::generators.controller_prefix') .
                ucfirst(Str::camel(Str::plural($singular))) .
                Config::get('thor::generators.controller_suffix');
    }

    protected function getClassInfo($classFullName)
    {
        $parts = explode('/', str_replace('\\', '/', trim($classFullName, '/\\ ')));
        $className = array_pop($parts);
        $classNamespace = implode('\\', $parts);
        $classNamespacePath = app_path() . '/src/' . trim(implode('/', $parts), '/\\ ');
        $classFile = $classNamespacePath . '/' . $className;
        return compact('classFullName', 'className', 'classNamespace', 'classNamespacePath', 'classFile');
    }

    public function buildVars($singular, $fields = array(), $transFields = false, $isPageable = false, $isImageable = false)
    {
        $plural = Str::plural($singular);
        $viewPrefix = $this->getViewPrefix($isPageable, $transFields);

        $model = $this->getClassInfo($this->getModelClass($singular));
        $controller = $this->getClassInfo($this->getControllerClass($singular));

        $migrationFile = app_path() . '/database/migrations/' . date('Y_m_d_His') . '_' . 'create_' . $plural . '_table';

        //$fields[] = array('integer', 'sorting', 'number');
        //$fields[] = array('boolean', 'is_published', 'checkbox');

        $viewParent = Config::get('thor::generators.view_parent');
        $viewSection = Config::get('thor::generators.view_section');

        if ($isPageable) {
            if (!is_array($fields)) {
                $fields = array();
            }
            if (!is_array($transFields)) {
                $transFields = array();
            }
            $fields = array_merge($fields, array(
                'taxonomy' => array('string', 'taxonomy', 'text'),
                'controller' => array('string', 'controller', 'text'),
                'action' => array('string', 'action', 'text'),
                'view' => array('string', 'view', 'text'),
                'is_https' => array('boolean', 'is_https', 'checkbox'),
                'is_indexable' => array('boolean', 'is_indexable', 'checkbox'),
                'is_deletable' => array('boolean', 'is_deletable', 'checkbox'),
                'sorting' => array('integer', 'sorting', 'number'),
                'status' => array('string', 'status', 'text'), // general pageable status
            ));
            $transFields = array_merge($transFields, array(
                'title' => array('string', 'title', 'text'),
                'content' => array('text', 'content', 'html'),
                'slug' => array('text', 'slug', 'text'),
                'window_title' => array('string', 'window_title', 'text'),
                'meta_description' => array('string', 'meta_description', 'text'),
                'meta_keywords' => array('string', 'meta_keywords', 'text'),
                'canonical_url' => array('string', 'canonical_url', 'text'),
                'redirect_url' => array('string', 'redirect_url', 'text'),
                'redirect_code' => array('string', 'redirect_code', 'text'), // redirect status code
            ));
        }

        $isTranslatable = (is_array($transFields) and ( count($transFields) > 0));

        if ($isTranslatable) {
            $transFields = array_merge($transFields, array(
                'translation_status' => array('string', 'translation_status', 'text'), // translation status
            ));
        }

        if (!is_array($transFields)) {
            $transFields = array();
        }

        $modelImplements = array();
        $modelUses = array();
        if ($isPageable) {
            $modelImplements[] = 'Models\\IPageable';
            $modelUses[] = 'Models\\TPageable';
        }
        if ($isTranslatable) {
            if (!$isPageable) {
                $modelImplements[] = 'Models\\ITranslatable';
            }
            $modelUses[] = 'Models\\TTranslatable';
        }
        if ($isImageable) {
            $modelImplements[] = 'Models\\IImageable';
            $modelUses[] = 'Models\\TImageable';
        }

        $model['implements'] = empty($modelImplements) ? '' : ('implements ' . implode(', ', $modelImplements));
        $model['uses'] = empty($modelUses) ? '' : ('use ' . implode(', ', $modelUses) . ';');

        return compact('singular', 'fields', 'transFields', 'isPageable', 'isTranslatable', 'isImageable'
                , 'viewPrefix', 'plural', 'model', 'controller', 'migrationFile', 'viewParent', 'viewSection');
    }

    public function createControllerFile(array $vars)
    {
        if (!is_dir($vars['controller']['classNamespacePath'])) {
            mkdir($vars['controller']['classNamespacePath'], 0755, true);
        }
        if (file_exists($vars['controller']['classFile'] . '.php')) {
            $vars['controller']['classFile'] .= '_' . date('Y_m_d_His');
        }
        file_put_contents($vars['controller']['classFile'] . '.php', View::make($vars['viewPrefix'] . 'controller', $vars)->render());
    }

    public function createModelFile(array $vars)
    {
        if (!is_dir($vars['model']['classNamespacePath'])) {
            mkdir($vars['model']['classNamespacePath'], 0755, true);
        }
        if (file_exists($vars['model']['classFile'] . '.php')) {
            $vars['model']['classFile'] .= '_' . date('Y_m_d_His');
        }
        file_put_contents($vars['model']['classFile'] . '.php', View::make($vars['viewPrefix'] . 'model', $vars)->render());

        if ($vars['isTranslatable']) {
            $textClassFile = $vars['model']['classFile'] . 'Text';
            if (file_exists($textClassFile . '.php')) {
                $textClassFile .= '_' . date('Y_m_d_His');
            }
            file_put_contents($textClassFile . '.php', View::make($vars['viewPrefix'] . 'model_text', $vars)->render());
        }
    }

    public function createMigrationFile(array $vars)
    {
        file_put_contents($vars['migrationFile'] . '.php', View::make($vars['viewPrefix'] . 'migration', $vars)->render());
    }

    public function createViewFiles(array $vars)
    {
        $viewsPath = app_path() . '/views/packages/thor/platform/backend/' . $vars['plural'] . '/';
        if (!is_dir($viewsPath)) {
            mkdir($viewsPath, 0755, true);
        }
        $views = array('create', 'edit', 'index', 'show');
        foreach ($views as $v) {
            $viewFile = $viewsPath . $v;
            if (file_exists($viewFile . '.blade.php')) {
                $viewFile .= '_' . date('Y_m_d_His');
            }
            file_put_contents($viewFile . '.blade.php', View::make($vars['viewPrefix'] . 'html.' . $v, $vars)->render());
        }
    }

    public function createResourceFiles($singular, $fields = array(), $transFields = false, $isPageable = false, $isImageable = false)
    {
        $vars = $this->buildVars($singular, $fields, $transFields, $isPageable, $isImageable);
        $this->createMigrationFile($vars);
        $this->createModelFile($vars);
        $this->createControllerFile($vars);
        $this->createViewFiles($vars);
    }

    /**
     * Defines router filters for LCRUD operations.
     * Checking if current user has permissions, if not it is redirected to the given page
     * 
     * @param string $singular Singular name of the resource
     * @param Redirect $notAllowedRedirect Redirection for not allowed users.
     * Defaults to Backend::url('error/?code=403')
     */
    public function createPermissionFilters($singular, Redirect $notAllowedRedirect = null)
    {
        $plural = Str::plural($singular);

        $permissions = array(
            'list_' . $plural,
            'create_' . $plural,
            'read_' . $plural,
            'update_' . $plural,
            'delete_' . $plural
        );
        
        if(empty($notAllowedRedirect)){
            $notAllowedRedirect = Redirect::to(Backend::url('/error?code=403'));
        }

        foreach ($permissions as $perm) {
            Route::filter('entrust.' . $perm, function() use($perm, $notAllowedRedirect) {
                if ((Backend::canAccess() and Entrust::can($perm)) === false) {
                    return $notAllowedRedirect;
                }
            });
        }
    }

    /**
     * Inserts new LCRUD permissions in the database
     * @param string $singular
     * @param boolean $assingToDevelopers
     * @return \Illuminate\Database\Eloquent\Collection|\Permission[]
     */
    public function createPermissions($singular, $assingToDevelopers = false)
    {
        $plural = \Str::plural($singular);

        $permissions = array(
            'list_' . $plural,
            'create_' . $plural,
            'read_' . $plural,
            'update_' . $plural,
            'delete_' . $plural
        );

        $records = new \Illuminate\Database\Eloquent\Collection();

        foreach ($permissions as $perm) {
            $records->add(\Permission::create(array(
                        'name' => $perm,
                        'display_name' => ucwords(Str::title(str_replace('_', ' ', $perm)))
            )));
        }
        if ($assingToDevelopers == true) {
            if ($developerRole = \Role::where('name', '=', 'developer')->first()) {
                $developerRole->attachPermissions($records);
            }
        }
        return $records;
    }

    /**
     * Defines routes for a given resource name
     * @param string $singular
     * @param boolean $withPermissionFilters
     */
    public function createResourceRoutes($singular, $withPermissionFilters = false)
    {
        $_this = $this;
        Route::langGroup(array('prefix' => Config::get('thor::generators.backend_base_route'), 'before' => 'auth.backend'), function()
                use($singular, $_this, $withPermissionFilters) {

            $plural = Str::plural($singular);
            $rt = 'backend.' . $plural;
            $ctrl = $this->getControllerClass($singular);
            $model = $this->getModelClass($singular);

            Route::model($singular, $model);
            Route::pattern($singular, '[0-9]+');

            if ($withPermissionFilters) {
                $_this->createPermissionFilters($singular);
            }

            Route::get($plural, array('as' => $rt . '.index', 'uses' => $ctrl . '@index', 'before' => 'entrust.list_' . $plural));
            Route::get($plural . '/{' . $singular . '}/show', array('as' => $rt . '.show', 'uses' => $ctrl . '@show', 'before' => 'entrust.read_' . $plural));
            Route::get($plural . '/create', array('as' => $rt . '.create', 'uses' => $ctrl . '@create', 'before' => 'entrust.create_' . $plural));
            Route::post($plural, array('as' => $rt . '.do_create', 'uses' => $ctrl . '@do_create', 'before' => 'entrust.create_' . $plural));
            Route::get($plural . '/{' . $singular . '}', array('as' => $rt . '.edit', 'uses' => $ctrl . '@edit', 'before' => 'entrust.update_' . $plural));
            Route::patch($plural . '/{' . $singular . '}', array('as' => $rt . '.do_edit', 'uses' => $ctrl . '@do_edit', 'before' => 'entrust.update_' . $plural));
            Route::delete($plural . '/{' . $singular . '}', array('as' => $rt . '.do_delete', 'uses' => $ctrl . '@do_delete', 'before' => 'entrust.delete_' . $plural));
        });
    }

}
