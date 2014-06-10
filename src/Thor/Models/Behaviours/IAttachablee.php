<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have related files
 */
interface IAttachablee
{

    public function files();

    public function hasFiles();

    public function firstFile();
}
