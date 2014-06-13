<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of ISortable interface
 * @todo Implement interface
 */
trait TSortable
{

    /**
     * 
     * @param mixed $query
     * @param string $direction 'asc' or 'desc'
     * @return mixed
     */
    public static function scopeSorted($query, $direction = 'asc')
    {
        return $query->orderBy('sorting', $direction);
    }

}
