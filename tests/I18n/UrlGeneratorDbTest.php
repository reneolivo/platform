<?php

namespace Thor\I18n;

class UrlGeneratorDbTest extends UrlGeneratorTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->prepareDatabase();
        $this->app['config']->set('thor::i18n.use_database', true);
        $this->app['translator']->resolve(); // resolve, now using DB
    }

}
