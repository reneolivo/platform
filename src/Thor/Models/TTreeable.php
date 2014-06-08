<?php

namespace Thor\Models;

/**
 * Implementation of ITreeable interface
 */
trait TTreeable
{

    /**
     * 
     * @return type
     */
    public function getParent()
    {
        return $this->belongsTo(get_class($this), 'parent_id', $this->primaryKey);
    }

    public function getAbsoluteParent()
    {
        $p = $this;
        while($p->hasParent()) {
            $p = $p->getParent();
        }
        return ($p->id == $this->id) ? false : $p;
    }

    public function hasParent()
    {
        return ($this->parent_id > 0);
    }

    public function getChildren()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->primaryKey);
    }

    public function hasChildren()
    {
        return $this->getChildren()->count() > 0;
    }

}
