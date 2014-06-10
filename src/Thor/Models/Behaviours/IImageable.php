<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have related images
 */
interface IImageable
{

    public function images();

    public function hasImages();

    public function firstImage();
}
