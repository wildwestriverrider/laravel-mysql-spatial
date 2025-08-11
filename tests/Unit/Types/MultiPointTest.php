<?php

use Wildwestriverrider\LaravelMysqlSpatial\Types\MultiPoint;
use Wildwestriverrider\LaravelMysqlSpatial\Types\Point;

class MultiPointTest extends BaseTestCase
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
        $multipoint = MultiPoint::fromWKT('MULTIPOINT((0 0),(1 0),(1 1))');
        $this->assertInstanceOf(MultiPoint::class, $multipoint);

        $this->assertEquals(3, $multipoint->count());
    }

    public function test_to_wkt()
    {
        $collection = [new Point(0, 0), new Point(0, 1), new Point(1, 1)];

        $multipoint = new MultiPoint($collection);

        $this->assertEquals('MULTIPOINT((0 0),(1 0),(1 1))', $multipoint->toWKT());
    }

    public function test_get_points()
    {
        $multipoint = MultiPoint::fromWKT('MULTIPOINT((0 0),(1 0),(1 1))');

        $this->assertInstanceOf(Point::class, $multipoint->getPoints()[0]);
    }

    public function test_from_json()
    {
        $multiPoint = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[1,1],[2,1],[2,2]]}');
        $this->assertInstanceOf(MultiPoint::class, $multiPoint);
        $multiPointPoints = $multiPoint->getGeometries();
        $this->assertCount(3, $multiPointPoints);
        $this->assertEquals(new Point(1, 1), $multiPointPoints[0]);
        $this->assertEquals(new Point(1, 2), $multiPointPoints[1]);
        $this->assertEquals(new Point(2, 2), $multiPointPoints[2]);
    }

    public function test_invalid_geo_json_exception()
    {
        $this->assertException(
            \Wildwestriverrider\LaravelMysqlSpatial\Exceptions\InvalidGeoJsonException::class,
            sprintf('Expected %s, got %s', GeoJson\Geometry\MultiPoint::class, GeoJson\Geometry\Point::class)
        );
        MultiPoint::fromJson('{"type":"Point","coordinates":[3.4,1.2]}');
    }

    public function test_json_serialize()
    {
        $collection = [new Point(0, 0), new Point(0, 1), new Point(1, 1)];

        $multipoint = new MultiPoint($collection);

        $this->assertInstanceOf(\GeoJson\Geometry\MultiPoint::class, $multipoint->jsonSerialize());
        $this->assertSame('{"type":"MultiPoint","coordinates":[[0,0],[1,0],[1,1]]}', json_encode($multipoint));
    }

    public function test_invalid_argument_exception_at_least_one_entry()
    {
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiPoint must contain at least 1 entry'
        );
        new MultiPoint([]);
    }

    public function test_invalid_argument_exception_not_array_of_line_string()
    {
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiPoint must be a collection of Wildwestriverrider\LaravelMysqlSpatial\Types\Point'
        );
        new MultiPoint([
            new Point(0, 0),
            1,
        ]);
    }

    public function test_array_access()
    {
        $point0 = new Point(0, 0);
        $point1 = new Point(1, 1);
        $multipoint = new MultiPoint([$point0, $point1]);

        // assert getting
        $this->assertEquals($point0, $multipoint[0]);
        $this->assertEquals($point1, $multipoint[1]);

        // assert setting
        $point2 = new Point(2, 2);
        $multipoint[] = $point2;
        $this->assertEquals($point2, $multipoint[2]);

        // assert invalid
        $this->assertException(
            InvalidArgumentException::class,
            'Wildwestriverrider\LaravelMysqlSpatial\Types\MultiPoint must be a collection of Wildwestriverrider\LaravelMysqlSpatial\Types\Point'
        );
        $multipoint[] = 1;
    }

    public function test_deprecated_prepend_point()
    {
        $point1 = new Point(1, 1);
        $point2 = new Point(2, 2);
        $multipoint = new MultiPoint([$point1, $point2]);

        $point0 = new Point(0, 0);
        $multipoint->prependPoint($point0);

        $this->assertEquals($point0, $multipoint[0]);
        $this->assertEquals($point1, $multipoint[1]);
        $this->assertEquals($point2, $multipoint[2]);
    }

    public function test_deprecated_append_point()
    {
        $point0 = new Point(0, 0);
        $point1 = new Point(1, 1);
        $multipoint = new MultiPoint([$point0, $point1]);

        $point2 = new Point(2, 2);
        $multipoint->appendPoint($point2);

        $this->assertEquals($point0, $multipoint[0]);
        $this->assertEquals($point1, $multipoint[1]);
        $this->assertEquals($point2, $multipoint[2]);
    }

    public function test_deprecated_insert_point()
    {
        $point1 = new Point(1, 1);
        $point3 = new Point(3, 3);
        $multipoint = new MultiPoint([$point1, $point3]);

        $point2 = new Point(2, 2);
        $multipoint->insertPoint(1, $point2);

        $this->assertEquals($point1, $multipoint[0]);
        $this->assertEquals($point2, $multipoint[1]);
        $this->assertEquals($point3, $multipoint[2]);

        $this->assertException(
            InvalidArgumentException::class,
            '$index is greater than the size of the array'
        );
        $multipoint->insertPoint(100, new Point(100, 100));
    }
}
