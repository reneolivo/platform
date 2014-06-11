<?php

namespace Thor\Models\Behaviours;

/**
 * Implementation of ITreeable interface
 */
trait TTreeable
{

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentRecord()
    {
        return $this->belongsTo(get_class($this), 'parent_id', $this->primaryKey);
    }

    public function parentRoot()
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

    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->primaryKey);
    }

    public function hasChildren()
    {
        return $this->getChildren()->count() > 0;
    }

}
