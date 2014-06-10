<?php

namespace Thor\Models;

use Thor\Models;

/**
 * Page model 
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
class Page extends Models\Base implements Behaviours\IPageable, Behaviours\ITranslatable
{

    use Behaviours\TPageable,
        Behaviours\TImageable, Behaviours\TTranslatable;

    protected $table = 'pages';
    protected $fillable = array(
        'taxonomy',
        'controller',
        'action',
        'view',
        'is_https',
        'is_indexable',
        'is_deletable',
        'sorting',
        'status',
    );
    public static $rules = array();

}
