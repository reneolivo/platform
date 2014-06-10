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
class Attachment extends Base implements Behaviours\ISortable, Behaviours\IPublishable
{

    use Behaviours\TSortable,
        Behaviours\TPublishable;

    protected $table = 'attachments';

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
        return '<a href="' . $this->url . '" ' . $attributes . '>' . $this->caption . '</a>';
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * File uploader handler (for dropzonejs)
     * @param string $attachableType
     * @param string $attachableId
     * @param callable $onupload
     * @param array $rules
     * @param string $inputName
     * @return \Response
     */
    public static function handleUpload($attachableType, $attachableId, $onupload = null, $rules = array('file' => 'mimes:zip,pdf,txt,md|max:3000'), $inputName = 'file')
    {
        $input = \Input::all();

        $validation = \Validator::make($input, $rules);

        if ($validation->fails()) {
            return \Response::make('Invalid file format', 400);
        }

        $file = \Input::file($inputName);
        $public_path = '/content/uploads/' . trim(strtolower(str_replace(array('Model', '\\'), array('', '_'), $attachableType)), '_ ') . '-' . $attachableId . '/';
        $ext = $file->getClientOriginalExtension();
        $filename = $ext ? preg_replace("/\\." . $ext . "$/", "", $file->getClientOriginalName()) : $file->getClientOriginalName();
        //$newFilename = \Str::slug($filename) . '_' . time() . '.' . strtolower($file->getClientOriginalExtension());
        $newFilename = sha1($filename . '_' . microtime()) . '.' . strtolower($file->getClientOriginalExtension());

        $record = new static(array(
            'attachable_id' => $attachableId,
            'attachable_type' => $attachableType,
            'caption' => $filename,
            'group' => \Input::get('group', 'default'),
            'path' => $public_path . $newFilename,
            'published_at' => date('Y-m-d H:i:s'),
            'sorting' => static::where('attachable_id', '=', $attachableId)->where('attachable_type', '=', $attachableId)->count() + 1
        ));

        if ($file->move(base_path('public' . $public_path), $newFilename)->isReadable()) {
            $record->save();
            if (is_callable($onupload)) {
                call_user_func($onupload, $record, $file, $newFilename);
            }
            return \Response::json('success', 200);
        }
        return \Response::json('error', 400);
    }

}
