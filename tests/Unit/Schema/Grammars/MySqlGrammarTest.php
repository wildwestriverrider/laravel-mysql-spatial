<?php

use Doctrine\DBAL\Exception;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Blueprint;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Grammars\MySqlGrammar;

class MySqlGrammarTest extends BaseTestCase
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

    public function test_adding_geometry()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometry('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` GEOMETRY not null', $statements[0]);
    }

    public function test_adding_point()
    {
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` POINT not null', $statements[0]);
    }

    public function test_adding_linestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` LINESTRING not null', $statements[0]);
    }

    public function test_adding_polygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` POLYGON not null', $statements[0]);
    }

    public function test_adding_multipoint()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTIPOINT not null', $statements[0]);
    }

    public function test_adding_multi_linestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTILINESTRING not null', $statements[0]);
    }

    public function test_adding_multi_polygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTIPOLYGON not null', $statements[0]);
    }

    public function test_adding_geometry_collection()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometrycollection('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` GEOMETRYCOLLECTION not null', $statements[0]);
    }

    public function test_adding_geometry_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometry('foo', null, 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` GEOMETRY not null srid 4326', $statements[0]);
    }

    public function test_adding_point_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->point('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` POINT not null srid 4326', $statements[0]);
    }

    public function test_adding_linestring_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` LINESTRING not null srid 4326', $statements[0]);
    }

    public function test_adding_polygon_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` POLYGON not null srid 4326', $statements[0]);
    }

    public function test_adding_multipoint_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTIPOINT not null srid 4326', $statements[0]);
    }

    public function test_adding_multi_linestring_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTILINESTRING not null srid 4326', $statements[0]);
    }

    public function test_adding_multi_polygon_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` MULTIPOLYGON not null srid 4326', $statements[0]);
    }

    public function test_adding_geometry_collection_with_srid()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometrycollection('foo', 4326);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` add `foo` GEOMETRYCOLLECTION not null srid 4326', $statements[0]);
    }

    /**
     * @throws Exception
     */
    public function test_add_remove_spatial_index()
    {
        $dsn = 'mysql:dbname=spatial_test;host=127.0.0.1;port=3306;';
        $pdo = new PDO($dsn, 'root', '');
        $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $conn = new MysqlConnection($pdo, 'database', 'prefix', $mysqlConfig);
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $blueprint->spatialIndex('foo');
        $addStatements = $blueprint->toSql($conn, $this->getGrammar());

        $this->assertCount(2, $addStatements);
        $this->assertEquals('alter table `test` add spatial `test_foo_spatial`(`foo`)', $addStatements[1]);

        $blueprint->dropSpatialIndex(['foo']);
        $blueprint->dropSpatialIndex('test_foo_spatial');
        $dropStatements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $expectedSql = 'alter table `test` drop index `test_foo_spatial`';
        $this->assertCount(4, $dropStatements);
        $this->assertEquals($expectedSql, $dropStatements[2]);
        $this->assertEquals($expectedSql, $dropStatements[3]);
    }

    //    /**
    //     * @return Connection
    //     */
    //    protected function getConnection(): Connection
    //    {
    //        return Mockery::mock(MysqlConnection::class);
    //    }

    protected function getGrammar(): MySqlGrammar
    {
        return new MySqlGrammar;
    }
}
