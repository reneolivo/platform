<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may be sorted using a 'sorting' field
 * @property integer $sorting Sortable field
 */
interface ISortable
{

    public static function scopeSorted($direction = 'asc');
}
