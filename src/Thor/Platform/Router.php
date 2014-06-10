<?php

namespace Thor\Platform;

use Thor\I18n,
    Thor\Models\Behaviours\IPageable;

/**
 * A Router with multilingual and pageable resolver features
 */
class Router extends I18n\Router
{

    protected $pageableClasses = array();

    public function registerPageable($classname)
    {
        $this->pageableClasses[$classname] = $classname;
    }

    public function registeredPageable($classname)
    {
        return isset($this->pageableClasses[$classname]);
    }

    public function unregisterPageable($classname)
    {
        if (isset($this->pageableClasses[$classname])) {
            unset($this->pageableClasses[$classname]);
        }
    }

    /**
     * Execute all pageable resolvers from the registered stack and returns the first found pageable
     * @param string $slug Full slug
     * @param string $slugField Slug field name to search in
     * @param int $langId Language to search the slug in (only used if this class has the Behaviours\TTranslatable trait)
     * @return Model\IPageable|false The first found pageable resource or false
     */
    public function resolvePageable($slug, $slugField = 'slug', $langId = null)
    {
        foreach ($this->pageableClasses as $i => $pageableClass) {
            $pageable = $pageableClass::resolve($slug, $slugField, $langId);
            if (($pageable instanceof IPageable) and ($pageable->exists())) {
                return $pageable;
            }
        }
        return false;
    }

}
