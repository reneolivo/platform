<?php

namespace Thor\Models\Behaviours;

use Config;

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

    public function url($extra = array())
    {
        return \URL::to($this->slug, $extra, $this->is_https ? true : false);
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function canonicalUrl()
    {
        if($this->exists()) {
            if(strlen(trim($this->canonical_url)) > 0) {
                $url = $this->canonical_url;
            } else {
                $url = $this->url();
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

    public static function slugize($str, $slugField = 'slug')
    {
        $slug = \Str::slug($str);
        $result = $slug;
        $i = 2;
        $search = static::resolve($slug, false, $slugField);
        while(is_object($search) and ( count($search) > 0)) {
            $result = $slug . '-' . $i;
            $search = static::resolve($result, false, $slugField);
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

        $controller = (empty($controller) ? (empty($this->controller) ? Config::get('thor::pageable_default_controller') : $this->controller) : $controller);
        $action = (empty($action) ? (empty($this->action) ? Config::get('thor::pageable_default_action') : $this->action) : $action);

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
     * @param boolean $onlyOne Return only the first record if present
     * @param string $slugField Slug field name to search in
     * @return \Illuminate\Database\Eloquent\Builder | static | false
     */
    public static function resolve($slug, $onlyOne = true, $slugField = 'slug')
    {
        $slug = trim($slug, '/ ');
        if(empty($slug)) {
            return false;
        }
        $modelClass = get_called_class() . 'Text';
        $statement = $modelClass::where($slugField, '=', $slug);

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
