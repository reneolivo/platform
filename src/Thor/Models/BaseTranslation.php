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
    protected $translatableModel = null;

    /**
     *
     * @var string 
     */
    protected $translatableForeign = null;

    /**
     *
     * @var array 
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (empty($this->translatableModel)) {
            $this->translatableModel = preg_replace('/(Translation)$/', '', get_class($this));
        }
        if (empty($this->translatableForeign)) {
            $this->translatableForeign = strtolower(basename(str_replace('\\', DIRECTORY_SEPARATOR, $this->translatableModel))) . '_id';
        }
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function translatable()
    {
        $translatableModel = $this->translatableModel;
        $translatableForeign = $this->translatableForeign;
        return $translatableModel::find($this->$translatableForeign);
    }

}
