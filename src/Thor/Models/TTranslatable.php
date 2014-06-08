<?php

namespace Thor\Models;

/**
 * Implementation of ITranslatable interface
 */
trait TTranslatable
{

    /**
     * Translations cache
     * @var array
     */
    protected $translations = array();

    public function __get($key)
    {
        if(isset($this->$key)) {
            return parent::__get($key); // look in the master model
        } elseif(isset($this->translation()->$key)) {
            return $this->translation()->$key; // look in the translation model
        } else {
            return parent::__get($key); // else rely on eloquent
        }
    }

    public function text($name, $langId = null)
    {
        return $this->translation($langId)->$name;
    }

    public function translation($langId = null)
    {
        if(empty($langId)) {
            $langId = \Lang::id();
        }
        if(isset($this->translations[$langId])) {
            return $this->translations[$langId];
        }
        $transl = $this->translations()->where('language_id', '=', $langId)->first();
        $trClass = (get_class($this) . 'Text');
        $this->translations[$langId] = ($transl ? $transl : new $trClass); // return existant or a new empty model
        return $this->translations[$langId];
    }

    public function translations()
    {
        return $this->hasMany((get_class($this) . 'Text'))->orderBy('language_id', 'asc');
    }

    public function clearTranslationsCache()
    {
        $this->translations = array();
    }

    /**
     * Returns an array with the translation and master merged fields
     * @return array
     */
    public function toMergedArray()
    {
        return array_merge($this->toArray(), $this->translation()->toArray());
    }

}
