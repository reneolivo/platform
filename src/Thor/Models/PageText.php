<?php

namespace Thor\Models;

use Thor\Models;

/**
 * Page text model 
 * @property string $taxonomy 
 * @property string $controller 
 * @property string $action 
 * @property string $view 
 * @property boolean $is_https 
 * @property boolean $is_indexable 
 * @property boolean $is_deletable 
 * @property integer $sorting 
 * @property string $status 
 * @property string $title 
 * @property text $content 
 * @property text $slug 
 * @property string $window_title 
 * @property string $meta_description 
 * @property string $meta_keywords 
 * @property string $canonical_url 
 * @property string $redirect_url 
 * @property string $redirect_code 
 * @property boolean $is_translated 
 * @property integer $language_id
 * @property integer $page_id
 * @property timestamp $created_at
 * @property timestamp $updated_at 
 */
class PageText extends Models\BaseText
{

    protected $table = 'page_texts';
    protected $fillable = array(
        'title',
        'content',
        'slug',
        'window_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'redirect_url',
        'redirect_code',
        'is_translated',
        'language_id',
        'page_id',
    );
    public static $rules = array();

    /**
     * Alias for master()
     * Returns the associated page
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        // in: foreach (Thor\Models\PageText::with('page')->get() as $page) // use eager loading!
        return $this->master();
    }

}
