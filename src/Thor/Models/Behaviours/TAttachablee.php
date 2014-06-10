<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of IAttachablee interface
 */
trait TAttachablee
{

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany|\Thor\Models\Attachment[]
     */
    public function files()
    {
        return $this->morphMany(\Config::get('thor::attachablee_attachment_model', '\\Thor\Models\\Attachment'), 'attachablee');
    }

    /**
     * 
     * @return boolean
     */
    public function hasFiles()
    {
        return $this->images()->count() > 0;
    }

    /**
     * 
     * @return \Thor\Models\Image|false
     */
    public function firstFile()
    {
        if($this->hasImages()) {
            return $this->images()->first();
        }
        return false;
    }

}
