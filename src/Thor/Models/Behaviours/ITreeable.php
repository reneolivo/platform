<?php

namespace Thor\Models\Behaviours;

/**
 * @property integer $parent_id
 */
interface ITreeable
{

    /**
     * Returns the parent record, specified in a parent_id column
     * @return static
     */
    public function parentRecord();

    /**
     * Returns the top most absolute parent of the tree
     * @return static
     */
    public function parentRoot();

    /**
     * @return boolean
     */
    public function hasParent();

    /**
     * @return boolean
     */
    public function hasChildren();

    /**
     * @return static[]
     */
    public function children();
}
