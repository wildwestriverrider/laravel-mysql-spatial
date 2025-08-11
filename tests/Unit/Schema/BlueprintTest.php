<?php

namespace Schema;

use BaseTestCase;
use Illuminate\Database\Schema\ColumnDefinition;
use Mockery;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Blueprint;

class BlueprintTest extends BaseTestCase
{
    /**
     * @var \Wildwestriverrider\LaravelMysqlSpatial\Schema\Blueprint
     */
    protected $blueprint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blueprint = Mockery::mock(Blueprint::class)
            ->makePartial()->shouldAllowMockingProtectedMethods();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        // Reset any custom error handlers
        restore_error_handler();

        // Reset any custom exception handlers
        restore_exception_handler();

        parent::tearDown();
    }

    public function test_geometry()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'geometry',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometry', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->geometry('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_point()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'point',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('point', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->point('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_linestring()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'linestring',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('linestring', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->linestring('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_polygon()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'polygon',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('polygon', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->polygon('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_point()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multipoint',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipoint', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multipoint('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_line_string()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multilinestring',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multilinestring', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multilinestring('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_polygon()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multipolygon',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipolygon', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multipolygon('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_geometry_collection()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'geometrycollection',
            'name' => 'col',
            'srid' => null,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometrycollection', 'col', ['srid' => null])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->geometrycollection('col');

        $this->assertSame($expectedCol, $result);
    }

    public function test_geometry_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'geometry',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometry', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->geometry('col', null, 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_point_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'point',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('point', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->point('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_linestring_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'linestring',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('linestring', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->linestring('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_polygon_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'polygon',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('polygon', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->polygon('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_point_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multipoint',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipoint', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multipoint('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_line_string_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multilinestring',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multilinestring', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multilinestring('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_multi_polygon_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'multipolygon',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipolygon', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->multipolygon('col', 4326);

        $this->assertSame($expectedCol, $result);
    }

    public function test_geometry_collection_with_srid()
    {
        $expectedCol = new ColumnDefinition([
            'type' => 'geometrycollection',
            'name' => 'col',
            'srid' => 4326,
        ]);

        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometrycollection', 'col', ['srid' => 4326])
            ->once()
            ->andReturn($expectedCol);

        $result = $this->blueprint->geometrycollection('col', 4326);

        $this->assertSame($expectedCol, $result);
    }
}
