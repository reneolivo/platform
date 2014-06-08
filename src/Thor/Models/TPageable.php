<?php

namespace Thor\Models;

/**
 * Implementation of IPageable interface
 * 
 * @property string $url
 * @property string $slug
 * @property string $partial_slug
 * @property string $window_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $canonical_url
 * @property string $redirect_url
 * @property string $redirect_status
 * 
 * @property boolean $is_https
 * @property boolean $is_published
 * @property boolean $is_indexable
 * 
 * @property string $sorting
 * 
 * @property timestamp $created_at
 * @property timestamp $updated_at
 * @property timestamp $published_at
 */
trait TPageable
{
    use TTranslatable;
    
    protected $defaultController = null;
    protected $defaultAction = null;

    public function url($langId = null)
    {
        $langCode = empty($langId) ? \Lang::code() : Language::find($langId)->code;
        return \URL::langTo($this->translation($langId)->slug, array(), $this->is_https ? true : false, $langCode);
    }

    public function slug($langId = null)
    {
        return $this->translation($langId)->slug;
    }

    public function canonicalUrl($langId = null)
    {
        if($this->exists()) {
            if(strlen(trim($this->translation($langId)->canonical_url)) > 0) {
                $url = $this->translation($langId)->canonical_url;
            } else {
                $url = $this->url($langId);
            }
        } else {
            return null;
        }

        return rtrim($url, '/'); //remove final trailing slash, because pages are not directories
    }

    public function metaRobots()
    {
        $this->is_indexable ? 'INDEX,FOLLOW' : 'NOINDEX,NOFOLLOW';
    }

    public function getUrlAttribute()
    {
        return $this->url();
    }

    public function isPublished()
    {
        return ($this->is_published == true) and ( strtotime($this->published_at) < time());
    }

    public static function scopePublished($query)
    {
        return $query->where('is_published', '=', 'published')->whereRaw('published_at < CURRENT_DATE');
    }

    public static function scopeSorted($query, $direction = 'asc')
    {
        return $query->orderBy('sorting', $direction);
    }

    public static function slugize($str, $langId = null, $slugField = 'slug')
    {
        $slug = \Str::slug($str);
        $result = $slug;
        $i = 2;
        $search = static::resolve($slug, $langId, false, $slugField);
        while(is_object($search) and ( count($search) > 0)) {
            $result = $slug . '-' . $i;
            $search = static::resolve($result, $langId, false, $slugField);
            $i++;
            if($i > 10) {
                break;
            }
        }
        return $result;
    }
    
    /**
     * This defines what this page must do when it's executed (e.g. it is resolved and you want to do something 'extra').
     * Tipically a controller and an action may be executed, but can be any type of class of your choice.
     * 
     * @param array $data Extra data passed to the action as the second argument (the first is always the pageable object)
     * @param string $controller Controller class override
     * @param string $action Controller action override
     * @return \Illuminate\View\View|false The redirect (if redirect_url and redirect_status are valid), the action return value or false
     */
    public function execute($data = array(), $controller = null, $action = null)
    {
        if(!($this->exists())) {
            return false;
        }

        if((intval($this->redirect_status) > 300) and ( filter_var($this->redirect_url, FILTER_VALIDATE_URL))) {
            return \Redirect::to($this->redirect_url, intval($this->redirect_status));
        }

        $controller = empty($controller) ? (empty($this->controller) ? $this->defaultController : $this->controller) : $controller;
        $action = empty($action) ? (empty($this->action) ? $this->defaultAction : $this->action) : $action;

        if(empty($controller) or empty($action)) {
            return false;
        }

        if(class_exists($controller)) {
            if(method_exists($controller, $action)) {
                $controller = new $controller();
                return $controller->$action($this, $data);
            }
        }
        return false;
    }

    /**
     * Finds a pageable by a slug string
     * @param string $slug Full slug
     * @param int|null $langId If false, it will be resolved without language conditions (matching any language)
     * @param boolean $onlyOne Return only the first record if present
     * @param string $slugField Slug field name to search in
     * @return \Illuminate\Database\Eloquent\Builder | static | false
     */
    public static function resolve($slug, $langId = null, $onlyOne = true, $slugField = 'slug')
    {
        $slug = trim($slug, '/ ');
        if(empty($slug)) {
            return false;
        }
        $modelClass = get_called_class() . 'Text';
        $statement = $modelClass::where($slugField, '=', $slug);

        if($langId !== false) {
            $statement = $statement->where('language_id', '=', empty($langId) ? \Lang::id() : $langId);
        }
        $records = $statement->get();
        $results = (count($records) == 0) ? false : $records;

        if($onlyOne == true) {
            if(($results == false) or ( count($results) != 1)) {
                return false;
            } else {
                return $results->first();
            }
        }

        return $results;
    }

}
