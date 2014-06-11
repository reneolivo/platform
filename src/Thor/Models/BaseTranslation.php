<?php

namespace Thor\Models;

/**
 * @property-read int $id Primary key value
 */
abstract class BaseTranslation extends Base
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
    protected $master_fk = null;

    /**
     *
     * @var array 
     */
    //protected $guarded = array();

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (empty($this->master_model)) {
            $this->master_model = preg_replace('/(Translation)$/', '', get_class($this));
        }
        if (empty($this->master_fk)) {
            $this->master_fk = strtolower(basename(str_replace('\\', DIRECTORY_SEPARATOR, $this->master_model))) . '_id';
        }
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master()
    {
        $master_model = $this->master_model;
        $master_fk = $this->master_fk;
        return $master_model::find($this->$master_fk);
    }

}
