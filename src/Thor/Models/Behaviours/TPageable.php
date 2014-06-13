<?php

namespace Thor\Models\Behaviours;

use Config;

trait TPageable
{

    public function url($extra = array(), $langId = null)
    {
        if ($this instanceof ITranslatable) {
            return \URL::langTo($this->translation($langId)->slug, array()
                            , ($this->is_https ? true : false)
                            , \Lang::code($langId));
        } else {
            return \URL::to($this->slug, $extra, $this->is_https ? true : false);
        }
    }

    public function getSlug($langId = null)
    {
        if ($this instanceof ITranslatable) {
            return $this->translation($langId) ? $this->translation($langId)->slug : '';
        }
        return $this->slug;
    }

    public function canonicalUrl($langId = null)
    {
        if ($this->exists()) {
            if ($this instanceof ITranslatable) {
                if (strlen(trim($this->translation($langId)->canonical_url)) > 0) {
                    $url = $this->translation($langId)->canonical_url;
                } else {
                    $url = $this->url($langId);
                }
            } else {
                if (strlen(trim($this->canonical_url)) > 0) {
                    $url = $this->canonical_url;
                } else {
                    $url = $this->url();
                }
            }
        } else {
            return null;
        }

        return rtrim($url, '/'); //remove final trailing slash, because pages are not directories
    }

    public function metaRobots()
    {
        return ($this->is_indexable ? 'INDEX,FOLLOW' : 'NOINDEX,NOFOLLOW');
    }

    public function getUrlAttribute()
    {
        return $this->url();
    }

    /**
     * Slugizes the given string and returns a valid unique slug (appending incrementing numbers if necessary)
     * @param string $str
     * @param string $slugField
     * @param int $langId Language to search the slug in (only used if this class has the Behaviours\TTranslatable trait)
     * @return string
     */
    public static function createUniqueSlug($str, $slugField = 'slug', $langId = null)
    {
        $slug = \Str::slug($str);
        $result = $slug;
        $suffix = 2;
        $search = static::resolve($slug, $slugField, false, $langId);
        while (is_object($search) and ( count($search) > 0)) {
            $result = $slug . '-' . $suffix;
            $search = static::resolve($result, $slugField, false, $langId);
            $suffix++;
        }
        return $result;
    }

    public static function scopeWithSlug($query, $slug)
    {
        
    }

    /**
     * Finds a pageable by a slug string
     * @param string $slug Full slug
     * @param string $slugField Slug field name to search in
     * @param int $langId Language to search the slug in (only used if this class has the Behaviours\TTranslatable trait)
     * @return \Illuminate\Database\Eloquent\Builder | static | false
     */
    public static function resolve($slug, $slugField = 'slug', $langId = null)
    {
        $slug = trim($slug, '/ ');
        if (empty($slug)) {
            return false;
        }
        $model = new static();
        $isTranslatable = ($model instanceof ITranslatable);
        $modelClass = get_real_class($model);
        $statement = $modelClass::where($slugField, '=', $slug);

        if ($isTranslatable) {
            $modelClass .= 'Translation';
            $statement = $modelClass::where($slugField, '=', $slug)->where('language_id', '=', empty($langId) ? \Lang::id() : $langId);
        } else {
            $statement = $modelClass::where($slugField, '=', $slug);
        }

        if ($model instanceof IPublishable) {
            $statement = $statement->published();
        }

        $records = $statement->get();

        $results = (count($records) == 0) ? false : $records;

        if ($results == false) {
            return false;
        } else {
            $pageable = $results->first();
            if ($isTranslatable) {
                return $pageable->master();
            }
            return $pageable;
        }

        return $results;
    }

    /**
     * This defines what this page must do when it's executed (e.g. it is resolved and you want to do something 'extra').
     * Tipically a controller and an action may be executed, but can be any type of class of your choice.
     * 
     * @param array $data Extra data passed to the action as the second argument (the first is always the pageable object)
     * @param string|\Closure $controller Controller class override
     * @param string $action Controller action override
     * @return \Illuminate\View\View|false The redirect (if redirect_url and redirect_code are valid), the action return value or false
     */
    public function execute($data = array(), $controller = null, $action = null)
    {
        if (!($this->exists())) {
            return false;
        }

        if ((intval($this->redirect_code) > 300) and ( filter_var($this->redirect_url, FILTER_VALIDATE_URL))) {
            return \Redirect::to($this->redirect_url, intval($this->redirect_code));
        }

        $controller = (empty($controller) ? (empty($this->controller) ?
                                Config::get('thor::pageable_default_controller') : $this->controller) : $controller);

        $action = (empty($action) ? (empty($this->action) ?
                                Config::get('thor::pageable_default_action') : $this->action) : $action);

        if (empty($controller) or empty($action)) {
            return false;
        }
        if ($controller instanceof \Closure) {
            return $controller($this, $data);
        } elseif (class_exists($controller) and method_exists($controller, $action)) {
            $controller = new $controller($this);
            return $controller->$action($data);
        }
        return false;
    }

}
