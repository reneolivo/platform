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

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|false
     */
    public function parentRoot()
    {
        $p = $this;
        while($p->hasParent()) {
            $p = $p->getParent();
        }
        return ($p->id == $this->id) ? false : $p;
    }

    /**
     * 
     * @return bool
     */
    public function hasParent()
    {
        return ($this->parent_id > 0);
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->primaryKey);
    }

    /**
     * 
     * @return bool
     */
    public function hasChildren()
    {
        return $this->getChildren()->count() > 0;
    }

}
