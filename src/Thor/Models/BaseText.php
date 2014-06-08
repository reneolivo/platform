<?php

namespace Thor\Models;

/**
 * @property-read int $id Primary key value
 */
abstract class BaseText extends Base
{

    /**
     *
     * @var string 
     */
    protected $master_model = null;

    /**
     *
     * @var string 
     */
    protected $master_primary = null;

    /**
     *
     * @var string 
     */
    protected $master_foreign = null;

    /**
     *
     * @var array 
     */
    protected $guarded = array();

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if(empty($this->master_model)) {
            $this->master_model = preg_replace('/(Text)$/', '', get_class($this));
        }
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master()
    {
        return $this->belongsTo($this->master_model, $this->master_foreign, $this->master_primary);
    }

}
