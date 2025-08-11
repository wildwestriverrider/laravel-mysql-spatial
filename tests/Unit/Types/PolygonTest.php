<?php

use Wildwestriverrider\LaravelMysqlSpatial\Types\LineString;
use Wildwestriverrider\LaravelMysqlSpatial\Types\Point;
use Wildwestriverrider\LaravelMysqlSpatial\Types\Polygon;

class PolygonTest extends BaseTestCase
{
    private Polygon $polygon;

    protected function setUp(): void
    {
        $collection = new LineString(
            [
                new Point(0, 0),
                new Point(0, 1),
                new Point(1, 1),
                new Point(1, 0),
                new Point(0, 0),
            ]
        );

        $this->polygon = new Polygon([$collection], 4326);
    }

    public function test_from_wkt()
    {
        $polygon = Polygon::fromWKT('POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))', 4326);
        $this->assertInstanceOf(Polygon::class, $polygon);

        $this->assertEquals(2, $polygon->count());
    }

    public function test_to_wkt()
    {
        $this->assertEquals('POLYGON((0 0,1 0,1 1,0 1,0 0))', $this->polygon->toWKT());
    }

    public function test_from_json()
    {
        $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[1,1],[2,1],[2,2],[1,2],[1,1]],[[1.2,1.2],[1.6,1.2],[1.6,1.8],[1.2,1.8],[1.2,1.2]]]}');
        $this->assertInstanceOf(Polygon::class, $polygon);
        $polygonLineStrings = $polygon->getGeometries();
        $this->assertCount(2, $polygonLineStrings);
        $this->assertEquals(new Point(1, 1), $polygonLineStrings[0][0]);
        $this->assertEquals(new Point(1, 2), $polygonLineStrings[0][1]);
        $this->assertEquals(new Point(2, 2), $polygonLineStrings[0][2]);
        $this->assertEquals(new Point(2, 1), $polygonLineStrings[0][3]);
        $this->assertEquals(new Point(1, 1), $polygonLineStrings[0][4]);
        $this->assertEquals(new Point(1.2, 1.2), $polygonLineStrings[1][0]);
        $this->assertEquals(new Point(1.2, 1.6), $polygonLineStrings[1][1]);
        $this->assertEquals(new Point(1.8, 1.6), $polygonLineStrings[1][2]);
        $this->assertEquals(new Point(1.8, 1.2), $polygonLineStrings[1][3]);
        $this->assertEquals(new Point(1.2, 1.2), $polygonLineStrings[1][4]);
    }

    public function test_invalid_geo_json_exception()
    {
        $this->assertException(
            \Wildwestriverrider\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException::class,
            'Expected GeoJson\Geometry\Polygon, got GeoJson\Geometry\Point'
        );
        Polygon::fromJson('{"type":"Point","coordinates":[3.4,1.2]}');
    }

    public function test_json_serialize()
    {
        $this->assertInstanceOf(\GeoJson\Geometry\Polygon::class, $this->polygon->jsonSerialize());
        $this->assertSame(
            '{"type":"Polygon","coordinates":[[[0,0],[1,0],[1,1],[0,1],[0,0]]]}',
            json_encode($this->polygon)
        );
    }
}
