<?php

namespace Thor\Models;

/**
 * Attachment model 
 * @property-read string $url 
 * @property string $caption 
 * @property string $path 
 * @property string $group 
 * @property integer $attachablee_id
 * @property string $attachablee_type
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class File extends Base implements Behaviours\ISortable, Behaviours\IPublishable
{

    use Behaviours\TSortable,
        Behaviours\TPublishable;

    protected $table = 'files';

    public static function boot()
    {
        parent::boot();

        // delete file if the record has been deleted
        static::deleted(function(Attachment $file) {
            $filepath = base_path('public/' . ltrim($file->path, '/'));
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
        });
    }

    public function isImage()
    {
        return ($this->type == 'image');
    }

    public function url()
    {
        return url($this->path);
    }

    public function getUrlAttribute()
    {
        return $this->url();
    }

    public function toHtml($attributes = '')
    {
        if ($this->isImage()) {
            return '<img src="' . $this->url . '" alt="' . $this->caption . '" ' . $attributes . ' />';
        } else {
            return '<a href="' . $this->url . '" ' . $attributes . '>' . $this->caption . '</a>';
        }
    }

    public function fileable()
    {
        return $this->morphTo();
    }

}
