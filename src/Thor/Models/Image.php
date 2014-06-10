<?php

namespace Thor\Models;

/**
 * Image model 
 * @property-read string $url 
 * @property string $caption 
 * @property string $path 
 * @property string $group 
 * @property integer $imageable_id 
 * @property string $imageable_type 
 * @property integer $sorting 
 * @property boolean $is_published 
 * @property string $sorting
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class Image extends Base implements Behaviours\ISortable, Behaviours\IPublishable
{
    use Behaviours\TSortable, Behaviours\TPublishable;
    
    protected $table = 'images';

    public static function boot()
    {
        parent::boot();

        // delete image file if the record has been deleted
        static::deleted(function(\Model\Image $image) {
            $filepath = base_path('public/' . ltrim($image->path, '/'));
            if(file_exists($filepath)) {
                @unlink($filepath);
            }
        });
    }

    public function url()
    {
        return url($this->path);
    }

    public function getUrlAttribute()
    {
        return $this->url();
    }

    public function asHtml($attributes = '')
    {
        return '<img src="' . $this->url . '" alt="' . $this->caption . '" ' . $attributes . ' />';
    }

    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * File uploader handler (for dropzonejs)
     * @param string $imageableType
     * @param string $imageableId
     * @param callable $onupload
     * @param array $rules
     * @param string $inputName
     * @return \Response
     */
    public static function handleUpload($imageableType, $imageableId, $onupload = null, $rules = array('file' => 'image|max:3000'), $inputName = 'file')
    {
        $input = \Input::all();

        $validation = \Validator::make($input, $rules);

        if($validation->fails()) {
            return \Response::make('Invalid image format', 400);
        }

        $file = \Input::file($inputName);
        $public_path = '/content/uploads/' . trim(strtolower(str_replace(array('Model', '\\'), array('', '_'), $imageableType)), '_ ') . '-' . $imageableId . '/';
        $ext = $file->getClientOriginalExtension();
        $filename = $ext ? preg_replace("/\\." . $ext . "$/", "", $file->getClientOriginalName()) : $file->getClientOriginalName();
        $newFilename = sha1($filename . '_' . microtime()) . '.' . strtolower($file->getClientOriginalExtension());

        $record = new static(array(
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
            'caption' => $filename,
            'group' => \Input::get('group', 'default'),
            'path' => $public_path . $newFilename,
            'sorting' => static::where('imageable_id', '=', $imageableId)->where('imageable_type', '=', $imageableId)->count() + 1
        ));

        if($file->move(base_path('public' . $public_path), $newFilename)->isReadable()) {
            $record->save();
            if(is_callable($onupload)) {
                call_user_func($onupload, $record, $file, $newFilename);
            }
            return \Response::json('success', 200);
        }
        return \Response::json('error', 400);
    }

}
