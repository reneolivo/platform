<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of IFileable interface
 */
class TFileable extends \Eloquent implements IFileable
{
    
    public function files($group = false, $type = false)
    {
        return $this->morphMany(\App::make('thor.models.file.class'), 'fileable');
    }
    
    public function images($group = false)
    {
        return $this->files($group, 'image');
    }
    
    public function firstFile($group = false, $type = false)
    {
        ;
    }
    
    public function firstImage($group = false)
    {
        return $this->firstFile($group, 'image');
    }
    
    public function hasFiles($group = false, $type = false)
    {
        ;
    }
    
    public function hasImages($group = false)
    {
        return $this->hasFiles($group, 'image');
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Thor\Models\File[]
     */
    public function files1()
    {
        return $this->morphMany(\Config::get('thor::fileable_file_model', '\\Thor\Models\\File'), 'fileable');
    }

    /**
     * 
     * @return boolean
     */
    public function hasFiles1()
    {
        return $this->images()->count() > 0;
    }

    /**
     * 
     * @return \Thor\Models\Image|false
     */
    public function firstFile1()
    {
        if ($this->hasImages()) {
            return $this->images()->first();
        }
        return false;
    }

}
