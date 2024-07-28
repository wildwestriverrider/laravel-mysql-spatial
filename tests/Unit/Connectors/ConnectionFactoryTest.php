<?php

use Illuminate\Container\Container;
use Stubs\PDOStub;
use Wildwestriverrider\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;

class ConnectionFactoryTest extends BaseTestCase
{
    public function tearDown(): void
    {
        Mockery::close();

        // Reset any custom error handlers
        restore_error_handler();

        // Reset any custom exception handlers
        restore_exception_handler();

        parent::tearDown();
    }

    public function testMakeCallsCreateConnection()
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=3306;';
        $pdo = new PDO($dsn, 'root', '');

        //$pdo = new PDOStub('127.0.0.1');

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('mysql', $pdo, 'database');

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }
//
//    public function testCreateConnectionDifferentDriver()
//    {
//        $pdo = new PDOStub();
//
//        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
//        $factory->shouldAllowMockingProtectedMethods();
//        $conn = $factory->createConnection('pgsql', $pdo, 'database');
//
//        $this->assertInstanceOf(\Illuminate\Database\PostgresConnection::class, $conn);
//    }
}
