<?php

namespace Thor\Generators;

use Route,
    Config,
    Entrust,
    Str,
    Redirect,
    View,
    Backend,
    Artisan;

class ModuleBuilder
{

    /**
     * 
     * @param string $singular
     * @param string|array $behaviours
     * @param string|array $generalFields
     * @param string|array $translatableFields
     * @param string|array $listableFields
     * @return \Thor\Generators\Parser
     */
    public function generate($singular, $behaviours = false, $generalFields = false, $translatableFields = false, $listableFields = false)
    {
        $res = new Parser($singular, $behaviours, $generalFields, $translatableFields, $listableFields);
        $this->createMigrationFile($res);
        $this->createModelFile($res);
        $this->createControllerFile($res);
        $this->createViewFiles($res);
        return $res;
    }

    /**
     * Defines routes for a given resource name
     * @param string $singular
     * @param boolean $withPermissionFilters
     */
    public function routes($singular, $withPermissionFilters = false, $controllerClass = null, $modelClass = null, Redirect $notAllowedRedirect = null)
    {
        $_this = $this;
        Route::langGroup(array('prefix' => Config::get('thor::backend.basepath'), 'before' => 'auth.backend'), function()
                use($_this, $singular, $withPermissionFilters, $controllerClass, $modelClass, $notAllowedRedirect) {

            $plural = Str::plural($singular);
            $rt = 'backend.' . $plural;
            $ctrl = ($controllerClass ? $controllerClass : ('\\Thor\\Backend\\' . ucfirst(Str::camel($plural)) . 'Controller'));
            $model = ($modelClass ? $modelClass : ('\\Thor\\Models\\' . ucfirst(Str::camel($singular))));

            Route::model($singular, $model);
            Route::pattern($singular, '[0-9]+');

            if ($withPermissionFilters) {
                $_this->filters($singular, $notAllowedRedirect);
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

    /**
     * Defines router filters for LCRUD operations.
     * Checking if current user has permissions, if not it is redirected to the given page
     * 
     * @param string $singular Singular name of the resource
     * @param Redirect $notAllowedRedirect Redirection for not allowed users.
     * Defaults to Backend::url('error/?code=403')
     */
    public function filters($singular, Redirect $notAllowedRedirect = null)
    {
        $plural = Str::plural($singular);

        $permissions = array(
            'list_' . $plural,
            'create_' . $plural,
            'read_' . $plural,
            'update_' . $plural,
            'delete_' . $plural
        );

        if (empty($notAllowedRedirect)) {
            $notAllowedRedirect = Redirect::to(Backend::url('/error?code=403'));
        }

        foreach ($permissions as $perm) {
            Route::filter('entrust.' . $perm, function() use($perm, $notAllowedRedirect) {
                if ((Backend::canBeAccessed() and Entrust::can($perm)) === false) {
                    return $notAllowedRedirect;
                }
            });
        }
    }

    public function createModule(array $input, $behaviours = false, $generalFields = false, $translatableFields = false, $listableFields = false)
    {
        $module = new \Thor\Models\Module();
        if (empty($input['name'])) {
            $input['name'] = \Str::singular($input['name']);
        }
        if (empty($input['display_name'])) {
            $input['display_name'] = \Str::plural(ucfirst($input['name']));
        }
        if (empty($input['icon'])) {
            $input['icon'] = 'fa-cube';
        }
        $input['name'] = strtolower(trim($input['name']));

        if ($module->validate($input)) {
            $resolver = $this->generate($input['name'], $behaviours, $generalFields, $translatableFields, $listableFields);
            Artisan::call('migrate');
            $this->createPermissions($resolver->singular, true);

            // finally:
            $input['is_pageable'] = $resolver->hasBehaviour('pageable');
            $input['controller_class'] = $resolver->controllerFullName;
            $input['model_class'] = $resolver->modelFullName;
            $exportedResolver = $resolver->export();
            unset($exportedResolver['resolver']);
            $input['metadata'] = serialize(array('input' => $input, 'resolver' => $exportedResolver));

            $module = $module->create($input);

            return $module;
        }
        return $module;
    }

    public function updateModule(\Thor\Models\Module $module, array $input, $behaviours = false, $generalFields = false, $translatableFields = false, $listableFields = false)
    {
        $module = new \Thor\Models\Module();
        if (empty($input['name'])) {
            $input['name'] = \Str::singular($input['name']);
        }
        if (empty($input['display_name'])) {
            $input['display_name'] = \Str::plural(ucfirst($input['name']));
        }
        if (empty($input['icon'])) {
            $input['icon'] = 'fa-cube';
        }
        $input['name'] = strtolower(trim($input['name']));

        if ($module->validate($input)) {
            $resolver = new Parser($input['name'], $behaviours, $generalFields, $translatableFields, $listableFields);
            //dd($module->metadata['resolver']);
            file_put_contents($module->metadata['resolver']->migrationFile . '.php', View::make('thor::generators.migration', $resolver->export())->render());
            $this->createModelFile($resolver);
            $this->createControllerFile($resolver);
            $this->createViewFiles($resolver);

            // finally:
            $input['is_pageable'] = $resolver->hasBehaviour('pageable');
            $input['controller_class'] = $resolver->controllerFullName;
            $input['model_class'] = $resolver->modelFullName;
            $exportedResolver = $resolver->export();
            unset($exportedResolver['resolver']);
            $input['metadata'] = serialize(array('input' => $input, 'resolver' => $exportedResolver));
            unset($input['behaviours']);
            return $module->update(array_only($input, $module->getFillable()));
        }
        return false;
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
                        'name' => strtolower($perm),
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
     * 
     * @param Parser $res Resource resolver
     */
    public function createControllerFile(Parser $res)
    {
        if (!is_dir($res->controllerPath)) {
            mkdir($res->controllerPath, 0755, true);
        }
        file_put_contents($res->controllerFile . '.php', View::make('thor::generators.controller', $res->export())->render());
    }

    /**
     * 
     * @param Parser $res Resource resolver
     */
    public function createModelFile(Parser $res)
    {
        if (!is_dir($res->modelPath)) {
            mkdir($res->modelPath, 0755, true);
        }

        file_put_contents($res->modelFile . '.php', View::make('thor::generators.model', $res->export())->render());

        if ($res->isTranslatable) {
            $textClassFile = $res->modelFile . 'Text';
            file_put_contents($textClassFile . '.php', View::make('thor::generators.model_text', $res->export())->render());
        }
    }

    /**
     * 
     * @param Parser $res Resource resolver
     */
    public function createMigrationFile(Parser $res)
    {
        file_put_contents($res->migrationFile . '.php', View::make('thor::generators.migration', $res->export())->render());
    }

    /**
     * 
     * @param Parser $res Resource resolver
     */
    public function createViewFiles(Parser $res, $force = false)
    {
        $viewsPath = app_path() . '/views/' . (trim($res->viewBasepath, '/ ')) . '/' . $res->plural . '/';
        if (!is_dir($viewsPath)) {
            mkdir($viewsPath, 0755, true);
        }
        $views = array('create', 'edit', 'index', 'show');
        foreach ($views as $v) {
            $viewFile = $viewsPath . $v;
            file_put_contents($viewFile . '.blade.php', View::make('thor::generators.views.' . $v, $res->export())->render());
        }
    }

}
