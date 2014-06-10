<?php

return array(
    'enabled' => false, // If this is disabled your application won't use multilingual features and the language won't be resolved
    'segment_index' => 1, // Language code position in the route segments (starting from 1)
    'use_database' => true, // Whether to rely on the database languages or the config ones only (false by default)
    'use_header' => false, // Whether to resolve from HTTP_ACCEPT_LANGUAGE as a route fallback or not (false by default)
    'available_locales' => array('en' => 'en_US', 'es' => 'es_ES', 'fr' => 'fr_FR', 'de' => 'de_DE', 'it' => 'it_IT')
);
