<?php

namespace Thor\I18n;

/**
 * Base Container-mocking class
 */
abstract class PackageTestCase extends AppTestCase
{

    protected function getPackageProviders()
    {
        return array('Thor\\ThorServiceProvider');
    }

    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // reset base path to point to our package's src directory
        $app['path.package_src'] = realpath(__DIR__ . '/../../../src');

        // load package config
        $config = include $app['path.package_src'] . '/config/i18n.php';
        foreach($config as $k => $v) {
            $app['config']->set('thor::i18n.' . $k, $v);
        }

        // set default db to sqlite memory
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
        
        $app['config']->set('thor::i18n.use_database', false);
    }

    /**
     * Overrides the current request and resolves the language again
     * @param string $path
     * @param string $method
     * @param array $query
     * @param array $post
     * @param array $server
     * @return \Illuminate\Http\Request
     */
    protected function prepareRequest($path = '/', $method = 'GET', $query = array(), $post = array(), $server = array())
    {
        $req = parent::prepareRequest($path, $method, $query, $post, $server);
        // we need to resolve the language for each new request
        $this->app['translator']->resolve();
        return $req;
    }

    /**
     * Migrate and seed
     */
    protected function prepareDatabase()
    {
        $migrations_dest = $this->app['path'].'/database/migrations';
        
        // Copy migrations
        $this->app['files']->copyDirectory($this->app['path.package_src'].'/../vendor/thor/models/src/migrations/',
                $migrations_dest);

        // create an artisan object for calling migrations
        $artisan = $this->app->make('artisan');
        // Migrate and seed
        $artisan->call('migrate', array(
            '--database' => 'testbench'
        ));
        $artisan->call('db:seed', array(
            '--class' => 'Thor\\Models\\Seeders\\LanguagesTableSeeder',
        ));
        
        // Delete copied migrations
        $this->app['files']->deleteDirectory($migrations_dest);
    }

    public function setUp()
    {
        parent::setUp();

        $this->prepareRequest('/'); // set default request to GET /
    }

}
