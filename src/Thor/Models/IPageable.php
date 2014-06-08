<?php

namespace Thor\Models;

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
interface IPageable extends ITranslatable
{

    /**
     * Returns the full url for this resource
     * @param int|null $langId
     */
    public function url($langId = null);

    /**
     * Returns the full slug in the desired language
     * @param int|null $langId
     */
    public function slug($langId = null);

    /**
     * Returns the full canonical url (or url by default)
     * @param int|null $langId
     */
    public function canonicalUrl($langId = null);

    public function metaRobots();

    /**
     * Returns the publish status, also, if the published_at date is a future date,
     * it shouldn't be considered published
     */
    public function isPublished();

    public static function scopeSorted($query, $direction = 'asc');

    public static function scopePublished($query);

    /**
     * Slugizes the given string and returns a valid unique slug for the -texts table (appending incrementing numbers if necessary)
     * @param string $str
     * @param int|null $langId
     * @param string $slugField
     * @return string
     */
    public static function slugize($str, $langId = null, $slugField = 'slug');

    /**
     * Finds a pageable by a slug string
     * @param string $slug Full slug
     * @param int|null $langId If false, it will be resolved without language conditions (matching any language)
     * @param boolean $onlyOne Return only the first record if present
     * @param string $slugField Slug field name to search in
     * @return \Illuminate\Database\Eloquent\Builder | static | false
     */
    public static function resolve($slug, $langId = null, $onlyOne = true, $slugField = 'slug');

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
