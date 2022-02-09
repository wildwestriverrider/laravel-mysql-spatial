<?php

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
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
