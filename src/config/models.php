<?php

use Thor\Generators\Field as Field;

return array(
    'behaviour_fields' => array(//behaviour required fields
        'flaggable' => array(
            Field::create('flags', array('type' => 'text', 'control' => 'text'))
        ),
        'pageable' => array(
        ),
        'publishable' => array(
            Field::create('published_at', array('type' => 'timestamp', 'control' => 'datepicker'))
        ),
        'sortable' => array(
            Field::create('sorting', array('type' => 'integer', 'control' => 'number'))
        ),
        'taggable' => array(
            Field::create('tags', array('type' => 'text', 'control' => 'text'))
        ),
        'treeable' => array(
            Field::create('parent_id', array('type' => 'integer', 'control' => 'select2',
                'foreignTable' => '=', 'foreignField' => 'id', 'foreignLabelField' => 'id'))
        ),
    )
);
