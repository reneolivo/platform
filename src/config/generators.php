<?php

return array(
    'backend_base_route' => 'backend',
    'view_parent' => 'thor::backend.layout',
    'view_section' => 'main',
    'controller_extends' => '\\Thor\\Backend\\Controller',
    'controller_prefix' => '\\Thor\\Backend\\',
    'controller_suffix' => 'Controller',
    'model_prefix' => '\\Thor\\Models\\',
    'model_suffix' => '',
);
