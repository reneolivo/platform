<?php

namespace Thor\I18n;

class TranslatorDbTest extends TranslatorTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->prepareDatabase();
        $this->app['config']->set('thor::i18n.use_database', true);
        $this->app['translator']->resolve(); // resolve, now using DB
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage The database has no active languages.
     */
    public function testExceptionDbIsEmpty()
    {
        \DB::table('languages')->delete();
        $this->prepareRequest('/');
    }

}
