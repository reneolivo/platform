<?php

namespace Thor\Models;

/**
 * Interface for models that may have related images
 */
interface IImageable
{

    public function images();

    public function hasImages();

    public function firstImage();
}
