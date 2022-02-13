<?php

use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Builder;
use PHPUnit\Framework\TestCase;
use Stubs\PDOStub;

class MysqlConnectionTest extends TestCase
{
    private $mysqlConnection;

    public function tearDown() : void
    {
        Mockery::close();
    }

    protected function setUp() : void
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=13306;';
        $pdo = new PDO($dsn, 'root', '');
        $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->mysqlConnection = new MysqlConnection($pdo, 'database', 'prefix', $mysqlConfig);
    }

    public function testGetSchemaBuilder()
    {
        $builder = $this->mysqlConnection->getSchemaBuilder();
        $this->assertInstanceOf(Builder::class, $builder);
    }
}
