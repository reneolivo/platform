<?php

namespace Thor\Generators;

class Field
{

    public $label;
    public $name;
    public $type;
    public $control;
    public $translatable;
    public $foreignTable;
    public $foreignField;
    public $foreignLabelField;

    /**
     * 
     * @param string $name
     * @param array $properties
     */
    public function __construct($name, array $properties = array())
    {
        $properties = array_merge(array(
            'label' => \Str::title(str_replace('_', ' ', $name)),
            'type' => 'string',
            'control' => 'text',
            'translatable' => false, // if true, the field belongs to a _texts table
            'foreignTable' => false,
            'foreignField' => false,
            'foreignLabelField' => false,
                ), $properties, array('name' => $name));

        foreach($properties as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * 
     * @param string $name
     * @param array $properties
     * @return static
     */
    public static function create($name, array $properties = array())
    {
        return new static($name, $properties);
    }

}
