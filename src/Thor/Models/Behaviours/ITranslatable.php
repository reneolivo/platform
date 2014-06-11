<?php

namespace Thor\Models\Behaviours;

interface ITranslatable
{

    /**
     * Returns a translation column value
     * @param string $name Column name
     * @param int $langId The language ID. NULL equals to the current language.
     * @return mixed
     */
    public function text($name, $langId = null);

    /**
     * Retrieves a translation in the given language
     * @param int $langId The language ID. NULL equals to the current language.
     * @return \Model\Baseml The translation record
     */
    public function translation($langId = null);

    /**
     * Retrieves all translations for this record
     * @return \Model\Baseml[] The translation record
     */
    public function translations();
}
