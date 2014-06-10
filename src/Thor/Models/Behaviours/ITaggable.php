<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have a field of tags
 * @property string $tags
 */
interface ITaggable
{

    public function tag($name);

    public function untag($name);

    public function hasTag($name);

    public static function scopeWithTag($tag);

    public static function scopeWithTags($tags);
}
