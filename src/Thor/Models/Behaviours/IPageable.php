<?php

namespace Thor\Models\Behaviours;

/**
 * @property string $controller
 * @property string $action
 * 
 * @property string $title
 * @property string $content
 * 
 * @property string $slug
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
interface IPageable
{

    /**
     * Returns the full url for this resource
     */
    public function url();

    /**
     * Returns the full slug
     */
    public function getSlug();

    /**
     * Returns the full canonical url (or url by default)
     */
    public function canonicalUrl();

    /**
     * Returns a meta robots string
     */
    public function metaRobots();

    /**
     * Slugizes the given string and returns a valid unique slug (appending incrementing numbers if necessary)
     * @param string $str
     * @param string $slugField
     * @return string
     */
    public static function slugize($str, $slugField = 'slug');

    /**
     * Finds a pageable by a slug string
     * @param string $slug Full slug
     * @param boolean $onlyOne Return only the first record if present
     * @param string $slugField Slug field name to search in
     * @return \Illuminate\Database\Eloquent\Builder | static | false
     */
    public static function resolve($slug, $onlyOne = true, $slugField = 'slug');

    /**
     * This defines what this page must do when it's executed (e.g. it is resolved and you want to do something 'extra').
     * Tipically a controller and an action may be executed, but can be any type of class of your choice.
     * 
     * @param array $data Extra data passed to the action as the second argument (the first is always the pageable object)
     * @param string $controller Controller class override
     * @param string $action Controller action override
     * @return \Illuminate\View\View|false The redirect (if redirect_url and redirect_status are valid), the action return value or false
     */
    public function execute($data = array(), $controller = null, $action = null);
}
