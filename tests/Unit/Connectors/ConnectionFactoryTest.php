<?php

use Illuminate\Container\Container;
use Stubs\PDOStub;
use Wildwestriverrider\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;

class ConnectionFactoryBaseTest extends BaseTestCase
{
    public function testMakeCallsCreateConnection()
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=13306;';
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
