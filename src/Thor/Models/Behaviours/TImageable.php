<?php

namespace Thor\Models\Behaviours;

/**
 * Trait for models that may have related images,
 * implementation of IImageable interface
 * 
 */
trait TImageable
{

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Thor\Models\Image[]
     */
    public function images()
    {
        return $this->morphMany(\Config::get('thor::imageable_image_model', '\\Thor\Models\\Image'), 'imageable');
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
