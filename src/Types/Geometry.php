<?php

namespace Wildwestriverrider\LaravelMysqlSpatial\Types;

use GeoIO\WKB\Parser\Parser;
use GeoJson\GeoJson;
use Illuminate\Contracts\Support\Jsonable;
use Wildwestriverrider\LaravelMysqlSpatial\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements \JsonSerializable, GeometryInterface, Jsonable
{
    protected static array $wkb_types = [
        1 => Point::class,
        2 => LineString::class,
        3 => Polygon::class,
        4 => MultiPoint::class,
        5 => MultiLineString::class,
        6 => MultiPolygon::class,
        7 => GeometryCollection::class,
    ];

    protected int $srid;

    public function __construct($srid = 0)
    {
        $this->srid = (int) $srid;
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    public function setSrid($srid): void
    {
        $this->srid = (int) $srid;
    }

    public static function getWKTArgument($value): string
    {
        $left = strpos($value, '(');
        $right = strrpos($value, ')');

        return substr($value, $left + 1, $right - $left - 1);
    }

    public static function getWKTClass($value): string
    {
        $left = strpos($value, '(');
        $type = trim(substr($value, 0, $left));

        switch (strtoupper($type)) {
            case 'POINT':
                return Point::class;
            case 'LINESTRING':
                return LineString::class;
            case 'POLYGON':
                return Polygon::class;
            case 'MULTIPOINT':
                return MultiPoint::class;
            case 'MULTILINESTRING':
                return MultiLineString::class;
            case 'MULTIPOLYGON':
                return MultiPolygon::class;
            case 'GEOMETRYCOLLECTION':
                return GeometryCollection::class;
            default:
                throw new UnknownWKTTypeException('Type was '.$type);
        }
    }

    public static function fromWKB($wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $srid = unpack('L', $srid)[1];

        $wkb = substr($wkb, 4);
        $parser = new Parser(new Factory);

        /** @var Geometry $parsed */
        $parsed = $parser->parse($wkb);

        if ($srid > 0) {
            $parsed->setSrid($srid);
        }

        return $parsed;
    }

    public static function fromWKT($wkt, $srid = null)
    {
        $wktArgument = static::getWKTArgument($wkt);

        return static::fromString($wktArgument, $srid);
    }

    public static function fromJson($geoJson)
    {
        if (is_string($geoJson)) {
            $geoJson = GeoJson::jsonUnserialize(json_decode($geoJson));
        }

        if ($geoJson->getType() === 'FeatureCollection') {
            return GeometryCollection::fromJson($geoJson);
        }

        if ($geoJson->getType() === 'Feature') {
            $geoJson = $geoJson->getGeometry();
        }

        $type = '\Wildwestriverrider\LaravelMysqlSpatial\Types\\'.$geoJson->getType();

        return $type::fromJson($geoJson);
    }

    public function toJson($options = 0): bool|string
    {
        return json_encode($this, $options);
    }
}
