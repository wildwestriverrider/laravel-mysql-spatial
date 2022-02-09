<?php

use Grimzy\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Grimzy\LaravelMysqlSpatial\MysqlConnection;
use Illuminate\Container\Container;
use Stubs\PDOStub;

class ConnectionFactoryBaseTest extends BaseTestCase
{
//    public function testMakeCallsCreateConnection()
//    {
//        $dsn = 'mysql:dbname=testdb;host=127.0.0.1';
//
//        $pdo = new PDOStub('127.0.0.1');
//
//        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
//        $factory->shouldAllowMockingProtectedMethods();
//        $conn = $factory->createConnection('mysql', $pdo, 'database');
//
//        $this->assertInstanceOf(MysqlConnection::class, $conn);
//    }
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
