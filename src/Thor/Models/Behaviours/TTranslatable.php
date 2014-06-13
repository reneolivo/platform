<?php

namespace Thor\Models\Behaviours;

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
        if (isset($this->$key)) {
            return parent::__get($key); // look in the translatable model
        } elseif ($this->hasTranslation() and isset($this->translation()->$key)) {
            return $this->translated($key); // look in the translation model
        } else {
            return parent::__get($key); // else .. rely on parent class
        }
    }

    /**
     * 
     * @param string $field
     * @param int $langId
     * @return mixed
     */
    public function translated($field, $langId = null)
    {
        return $this->translation($langId)->$field;
    }

    /**
     * 
     * @param int $langId
     * @return boolean
     */
    public function hasTranslation($langId = null)
    {
        return $this->translation($langId ? $langId : \Lang::id())->exists();
    }

    /**
     * 
     * @param int $langId
     * @return BaseTranslation
     */
    public function translation($langId = null)
    {
        if (empty($langId)) {
            $langId = \Lang::id();
        }
        if (isset($this->translations[$langId])) {
            return $this->translations[$langId];
        }
        $transl = $this->translations()->where('language_id', '=', $langId)->first();
        $transClass = (get_class($this) . 'Translation');
        $this->translations[$langId] = ($transl ? $transl : new $transClass); // return existant or a new empty model
        return $this->translations[$langId];
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany((get_class($this) . 'Translation'))->orderBy('language_id', 'asc');
    }

    /**
     * Returns an array with the translation and translatable merged fields
     * @return array
     */
    public function toMergedArray()
    {
        return array_merge($this->toArray(), $this->translation()->toArray());
    }

    /**
     * 
     */
    public function clearTranslationsCache()
    {
        $this->translations = array();
    }

}
