<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of ISortable interface
 * @todo Implement interface
 */
trait TSortable
{

    public static function scopeSorted($query, $direction = 'asc')
    {
        return $query->orderBy('sorting', $direction);
    }

}
