<?php

namespace Thor\Platform;

class PageableManager
{

    /**
     *
     * @var array
     */
    protected $registered = array();

    /**
     *
     * @var \Thor\Models\Behaviours\IPageable|false 
     */
    protected $found = false;

    /**
     * Execute all pageable resolvers from the registered stack and returns the first found pageable
     * @param string $slug Full slug
     * @param string $slugField Slug field name to search in
     * @param boolean $onlyOne Return only the first record if present
     * @param int $langId Language to search the slug in (only used if this class has the Behaviours\TTranslatable trait)
     * @return Model\IPageable|false The first found pageable resource or false
     */
    public function resolve($slug, $slugField = 'slug', $onlyOne = true, $langId = null)
    {
        $this->found = false;
        foreach($this->registered as $i => $pageableClass) {
            $pageable = $pageableClass::resolve($slug, $slugField, $onlyOne, $langId);
            //echo $i.' <br>';dd($pageable->get());
            if($pageable instanceof \Thor\Models\Behaviours\IPageable) {
                $this->found = $pageable;
                return $pageable;
            }
        }
        return $this->found;
    }

    /**
     * Returns the current found pageable
     * @return \Thor\Models\Behaviours\IPageable|false 
     */
    public function found()
    {
        return $this->found;
    }
    
    public function isFound(){
        return ($this->found instanceof \Thor\Models\Behaviours\IPageable) and ($this->found->exists());
    }

    /**
     * Registers a pageable class name
     */
    public function register($pageableClass)
    {
        $this->registered[] = $pageableClass;
    }

    public function registered()
    {
        return $this->registered;
    }

    public function setRegistered(array $registered)
    {
        $this->registered = $registered;
    }

}
