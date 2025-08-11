<?php

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Builder;

class MysqlConnectionTest extends TestCase
{
    private MysqlConnection $mysqlConnection;

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=3306;';
        $pdo = new PDO($dsn, 'root', '');
        $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->mysqlConnection = new MysqlConnection($pdo, 'database', 'prefix', $mysqlConfig);
    }

    public function test_get_schema_builder()
    {
        $builder = $this->mysqlConnection->getSchemaBuilder();
        $this->assertInstanceOf(Builder::class, $builder);
    }
}
