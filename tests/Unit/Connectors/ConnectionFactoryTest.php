<?php

use Doctrine\DBAL\Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Stubs\PDOStub;
use Wildwestriverrider\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;

class ConnectionFactoryTest extends BaseTestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        // Reset any custom error handlers
        restore_error_handler();

        // Reset any custom exception handlers
        restore_exception_handler();

        parent::tearDown();
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function test_make_calls_create_connection()
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=3306;';
        $pdo = new \PDO($dsn, 'root', '');

        // $pdo = new PDOStub('127.0.0.1');

        $factory = Mockery::mock(ConnectionFactory::class, [new Container])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('mysql', $pdo, 'database');

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function test_create_connection_different_driver()
    {
        $pdo = new PDOStub;

        $factory = Mockery::mock(ConnectionFactory::class, [new Container])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('pgsql', $pdo, 'database');

        $this->assertInstanceOf(\Illuminate\Database\PostgresConnection::class, $conn);
    }
}
