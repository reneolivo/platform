<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have related files
 */
interface IFileable
{

    public function files($group = false, $type = false);

    public function hasFiles($group = false, $type = false);

    public function firstFile($group = false, $type = false);

    public function images($group = false);

    public function hasImages($group = false);

    public function firstImage($group = false);
}
