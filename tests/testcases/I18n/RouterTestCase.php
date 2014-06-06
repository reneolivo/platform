<?php

namespace Thor\I18n;

class RouterTestCase extends PackageTestCase
{

    public function testRouteFacadeIsSwapped()
    {
        $this->assertArrayHasKey('router', $this->app);
        $this->assertArrayHasKey('thor.router', $this->app);
        $this->assertInstanceOf('Thor\\I18n\\Router', $this->app['router']);
    }

    /**
     * @covers  \Thor\I18n\Router::langGroup
     * @dataProvider langCodeProvider
     */
    public function testLangGroup($langCode)
    {
        $this->prepareRequest('/' . $langCode . '/');
        $router = $this->app['router'];

        $this->app['router']->langGroup(function () use ($router) {
            $router->get('foobar', 'foobar');
        });

        foreach($this->app['router']->getRoutes() as $r) {
            $route = $r;
            break;
        }

        $this->assertEquals($langCode . '/foobar', $route->getPath());
    }

    /**
     * @covers  \Thor\I18n\Router::langGroup
     * @dataProvider langCodeProvider
     */
    public function testLangGroupWithPrefix($langCode)
    {
        $this->prepareRequest('/' . $langCode . '/');
        $router = $this->app['router'];

        $this->app['router']->langGroup(array('prefix' => 'xx'), function () use ($router) {
            $router->get('foobar', 'foobar');
        });

        foreach($this->app['router']->getRoutes() as $r) {
            $route = $r;
            break;
        }

        $this->assertEquals($langCode . '/xx/foobar', $route->getPath());
    }

    /**
     * @covers  \Thor\I18n\Router::langGroup
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $this->app['router']->langGroup('foobar');
    }

    /**
     * @covers  \Thor\I18n\Router::langGroup
     */
    public function testRootPath()
    {
        $this->prepareRequest('/');

        $app = $this->app;
        $this->app['router']->get('/', function() use($app) {
            return $app['translator']->locale();
        });

        $resp = $this->call('GET', '/');

        $this->assertResponseOk();
        $this->assertEquals('en_US', $resp->getContent());
    }

    /**
     * @covers  \Thor\I18n\Router::langGroup
     */
    public function testRootPathWithHeaderFallback()
    {
        $this->app['config']->set('thor::i18n.use_header', true);
        $this->prepareRequest('/', 'GET', array(), array(), array(
            'HTTP_ACCEPT_LANGUAGE' => 'it,it-it,fr,es;'
        ));

        $app = $this->app;
        $this->app['router']->get('/', function() use($app) {
            return $app['translator']->locale();
        });

        $resp = $this->call('GET', '/');

        $this->assertResponseOk();
        $this->assertEquals('it_IT', $resp->getContent());
    }

    public function langCodeProvider()
    {
        return array(
            array('en'),
            array('es'),
            array('fr'),
            array('de'),
            array('it')
        );
    }

}
