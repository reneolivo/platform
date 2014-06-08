<?php

namespace Thor\Models;

/**
 * Trait for models that may have related images,
 * implementation of IImageable interface
 * 
 * @property \Thor\Models\IImageable $imageable Imageable instance
 */
trait TImageable
{

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Thor\Models\Image[]
     */
    public function images()
    {
        return $this->morphMany('\\Thor\Models\\Image', 'imageable')->orderBy('sorting', 'asc');
    }

    /**
     * 
     * @return boolean
     */
    public function hasImages()
    {
        return $this->images()->count() > 0;
    }

    /**
     * 
     * @return \Thor\Models\Image|false
     */
    public function firstImage()
    {
        if($this->hasImages()) {
            return $this->images()->first();
        }
        return false;
    }

}
