<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have a field of flags
 * @property string $flags
 */
interface IFlaggable
{

    public function flag($name);

    public function unflag($name);

    public function hasFlag($name);

    public static function scopeWithFlag($flag);

    public static function scopeWithFlags($flags);
}
