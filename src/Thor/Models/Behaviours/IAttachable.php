<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may have related files
 */
interface IAttachable
{

    public function files();

    public function hasFiles();

    public function firstFile();
}
