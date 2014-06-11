<?php

namespace Thor\I18n;

use Closure,
    Lang,
    Config;

/**
 * A Router with multilingual resolver features
 */
class Router extends \Illuminate\Routing\Router
{

    /**
     * Create a route group with shared attributes, 
     * using the current language code as the prefix.
     * 
     * If you specify a prefix in the attributes variable
     * it will be appended to the language prefix like: en/myprefix
     *
     * @param  array|Closure    $attributes Attributes or callback
     * @param  Closure|null     $callback
     * @return void
     */
    public function langGroup($attributes, Closure $callback = null)
    {
        if (Config::get('thor::i18n.enabled')) {
            if ($attributes instanceof Closure) {
                return $this->group(array('prefix' => Lang::code()), $attributes);
            } elseif (is_array($attributes)) {
                $attributes['prefix'] = trim(isset($attributes['prefix']) ? (Lang::code() . '/' . $attributes['prefix']) : Lang::code(), '/');
                $this->group($attributes, $callback);
            } else {
                throw new \InvalidArgumentException('First argument must be of type Closure or array');
            }
        } else {
            if ($attributes instanceof Closure) {
                $attributes();
            } else {
                return parent::group($attributes, $callback);
            }
        }
    }

}
