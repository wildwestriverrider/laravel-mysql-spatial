<?php

use Wildwestriverrider\LaravelMysqlSpatial\Types\LineString;
use Wildwestriverrider\LaravelMysqlSpatial\Types\MultiLineString;
use Wildwestriverrider\LaravelMysqlSpatial\Types\Point;

class MultiLineStringTest extends BaseTestCase
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

    public function test_from_wkt()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING((0 0,1 1,1 2),(2 3,3 2,5 4))');
        $this->assertInstanceOf(MultiLineString::class, $multilinestring);

        $this->assertSame(2, $multilinestring->count());
    }

    public function test_to_wkt()
    {
        $collection = new LineString([
            new Point(0, 0),
            new Point(0, 1),
            new Point(1, 1),
            new Point(1, 0),
            new Point(0, 0),
        ]);

        $multilinestring = new MultiLineString([$collection]);

        $this->assertSame('MULTILINESTRING((0 0,1 0,1 1,0 1,0 0))', $multilinestring->toWKT());
    }

    public function test_from_json()
    {
        $multiLineString = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[1,1],[1,2],[1,3]],[[2,1],[2,2],[2,3]]]}');
        $this->assertInstanceOf(MultiLineString::class, $multiLineString);
        $multiLineStringLineStrings = $multiLineString->getGeometries();
        $this->assertCount(2, $multiLineStringLineStrings);
        $this->assertEquals(new Point(1, 1), $multiLineStringLineStrings[0][0]);
        $this->assertEquals(new Point(2, 1), $multiLineStringLineStrings[0][1]);
        $this->assertEquals(new Point(3, 1), $multiLineStringLineStrings[0][2]);
        $this->assertEquals(new Point(1, 2), $multiLineStringLineStrings[1][0]);
        $this->assertEquals(new Point(2, 2), $multiLineStringLineStrings[1][1]);
        $this->assertEquals(new Point(3, 2), $multiLineStringLineStrings[1][2]);
    }

    public function test_invalid_geo_json_exception()
    {
        $this->assertException(
            \Wildwestriverrider\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException::class,
            sprintf('Expected %s, got %s', GeoJson\Geometry\MultiLineString::class, GeoJson\Geometry\Point::class)
        );
        MultiLineString::fromJson('{"type":"Point","coordinates":[3.4,1.2]}');
    }

    public function test_json_serialize()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING((0 0,1 1,1 2),(2 3,3 2,5 4))');

        $this->assertInstanceOf(\GeoJson\Geometry\MultiLineString::class, $multilinestring->jsonSerialize());
        $this->assertSame('{"type":"MultiLineString","coordinates":[[[0,0],[1,1],[1,2]],[[2,3],[3,2],[5,4]]]}', json_encode($multilinestring));
    }

    public function test_invalid_argument_exception_at_least_one_entry()
    {
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiLineString must contain at least 1 entry'
        );
        new MultiLineString([]);
    }

    public function test_invalid_argument_exception_not_array_of_line_string()
    {
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiLineString must be a collection of Wildwestriverrider\LaravelMysqlSpatial\Types\LineString'
        );
        new MultiLineString([
            new LineString([new Point(0, 0), new Point(1, 1)]),
            new Point(0, 1),
        ]);
    }

    public function test_array_access()
    {
        $linestring0 = new LineString([
            new Point(0, 0),
            new Point(1, 1),
        ]);
        $linestring1 = new LineString([
            new Point(1, 1),
            new Point(2, 2),
        ]);

        $multilinestring = new MultiLineString([$linestring0, $linestring1]);

        // assert getting
        $this->assertEquals($linestring0, $multilinestring[0]);
        $this->assertEquals($linestring1, $multilinestring[1]);

        // assert setting
        $linestring2 = new LineString([
            new Point(2, 2),
            new Point(3, 3),
        ]);
        $multilinestring[] = $linestring2;
        $this->assertEquals($linestring2, $multilinestring[2]);

        // assert invalid
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiLineString must be a collection of Wildwestriverrider\LaravelMysqlSpatial\Types\LineString'
        );
        $multilinestring[] = 1;
    }
}
