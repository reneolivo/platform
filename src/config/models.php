<?php

// Core model classes
return array(
    'classes' => array(
        'language' => 'Thor\Models\Language',
        'permission' => 'Thor\Models\Permission',
        'role' => 'Thor\Models\Role',
        'user' => 'Thor\Models\User'
    ),
    'pageable_default_controller' => '\\Thor\\Platform\\PageableController',
    'pageable_default_action' => 'defaultAction',
    'pageable_default_view' => 'default',
);
