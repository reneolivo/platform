<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of IFileable interface
 */
class TFileable extends \Eloquent implements IFileable
{

    public function files($group = false, $type = false)
    {
        $result = $this->morphMany(\App::make('thor.models.file.class'), 'fileable');
        if ($group != false) {
            $result = $result->where('group', '=', $group);
        }
        if ($type != false) {
            $result = $result->where('type', '=', $type);
        }
        return $result;
    }

    public function images($group = false)
    {
        return $this->files($group, 'image');
    }

    public function firstFile($group = false, $type = false)
    {
        if ($this->hasFiles($group, $type)) {
            return $this->files($group, $type)->first();
        }
        return false;
    }

    public function firstImage($group = false)
    {
        return $this->firstFile($group, 'image');
    }

    public function hasFiles($group = false, $type = false)
    {
        return ($this->files($group, $type)->count() > 0);
    }

    public function hasImages($group = false)
    {
        return $this->hasFiles($group, 'image');
    }

}
