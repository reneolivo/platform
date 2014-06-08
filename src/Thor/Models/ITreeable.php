<?php

namespace Thor\Models;

interface ITreeable
{

    /**
     * Returns the parent record, specified in a parent_id column
     * @return static
     */
    public function getParent();

    /**
     * Returns the top most absolute parent of the tree
     * @return static
     */
    public function getAbsoluteParent();

    public function hasParent();

    public function hasChildren();

    /**
     * @return static[]
     */
    public function getChildren();
}
