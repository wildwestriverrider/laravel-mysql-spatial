<?php

use Grimzy\LaravelMysqlSpatial\SpatialServiceProvider;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Application;

abstract class BaseTestCase extends TestCase
{
    /**
     * Boots the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../../vendor/laravel/laravel/bootstrap/app.php';
        $app->register(SpatialServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        config()->set('database.default', 'mysql');
        config()->set('database.connections.mysql.host', env('DB_HOST'));
        config()->set('database.connections.mysql.port', env('DB_PORT'));
        config()->set('database.connections.mysql.database', env('DB_DATABASE'));
        config()->set('database.connections.mysql.username', env('DB_USERNAME'));
        config()->set('database.connections.mysql.password', env('DB_PASSWORD'));
        config()->set('database.connections.mysql.modes', [
            'ONLY_FULL_GROUP_BY',
            'STRICT_TRANS_TABLES',
            'NO_ZERO_IN_DATE',
            'NO_ZERO_DATE',
            'ERROR_FOR_DIVISION_BY_ZERO',
            'NO_ENGINE_SUBSTITUTION',
        ]);

        return $app;
    }

    public function tearDown() : void
    {
        Mockery::close();
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Grimzy\LaravelMysqlSpatial\SpatialServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'spatial_test');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-policies-to-json_table.php.stub';
        $migration->up();
        */
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        config()->set('database.default', 'spatial_test');
        config()->set('database.connections.spatial_test', [
            'driver'   => 'mysql',
            'database' => 'spatial_test',
            'prefix'   => '',
        ]);
    }

    protected function assertException($exceptionName, $exceptionMessage = '', $exceptionCode = 0)
    {
        if (method_exists(parent::class, 'expectException')) {
            parent::expectException($exceptionName);
            parent::expectExceptionMessage($exceptionMessage);
            parent::expectExceptionCode($exceptionCode);
        } else {
            $this->setExpectedException($exceptionName, $exceptionMessage, $exceptionCode);
        }
    }
}
