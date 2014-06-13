<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of ITaggable interface
 * @param array $tags
 */
trait TTaggable
{

    public function getTagsAttribute()
    {
        if (is_array($this->attributes['tags'])) {
            // leave the possibility to the real class for handle this in the boot() fn
            return $this->attributes['tags'];
        }
        return explode('|', trim($this->attributes['tags'], '|'));
    }

    public function setTagsAttribute($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $value = array_unique($value);

        if (is_array($this->attributes['tags'])) {
            // leave the possibility to the real class for handle this in the boot() fn
            $this->attributes['tags'] = $value;
        } else {
            $this->attributes['tags'] = '|' . trim(implode('|', $value), '|') . '|';
        }
    }

    /**
     * Adds a tag
     * @param string|array $tag
     */
    public function tag($tag)
    {
        if (is_array($tag)) {
            foreach ($tag as $t) {
                $this->tag($t);
            }
        }
        $tags = $this->getTagsAttribute();
        $tags[] = $tag;
        $this->setTagsAttribute($tags);
    }

    /**
     * Removes a tag
     * @param string|array $tag
     */
    public function untag($tag)
    {
        if (is_array($tag)) {
            foreach ($tag as $t) {
                $this->untag($t);
            }
        }
        $tags = $this->getTagsAttribute();
        $i = array_search($tag, $tags, true);
        if ($i !== false) {
            unset($tags[$i]);
        }
        $this->setTagsAttribute(array_unique($tags));
    }

    /**
     * Checks if a tag exists
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        return in_array($tag, $this->getTagsAttribute());
    }

    /**
     * Returns the records that have the specified tag(s)
     * @param mixed $query
     * @param string|array $tags
     * @param string $mode 'and' or 'or'
     * @return mixed
     */
    public static function scopeTaggedWith($query, $tags, $mode = 'and')
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }
        foreach ($tags as $i => $tag) {
            if (($mode == 'and') or ( $i == 0)) {
                $query = $query->where('tags', 'LIKE', "%|$tag|%");
            } elseif ($mode == 'or') {
                $query = $query->orWhere('tags', 'LIKE', "%|$tag|%");
            }
        }
        return $query;
    }

}
