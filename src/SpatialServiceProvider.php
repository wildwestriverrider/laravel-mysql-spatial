<?php

namespace Wildwestriverrider\LaravelMysqlSpatial;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;
use Wildwestriverrider\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\Geometry;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\GeometryCollection;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\LineString;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\MultiLineString;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\MultiPoint;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\MultiPolygon;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\Point;
use Wildwestriverrider\LaravelMysqlSpatial\Doctrine\Polygon;

/**
 * Class DatabaseServiceProvider.
 */
class SpatialServiceProvider extends DatabaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        $this->app->bind('db.schema', function ($app) {
            return $app['db']->connection()->getSchemaBuilder();
        });

        if (class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry' => Geometry::class,
                'point' => Point::class,
                'linestring' => LineString::class,
                'polygon' => Polygon::class,
                'multipoint' => MultiPoint::class,
                'multilinestring' => MultiLineString::class,
                'multipolygon' => MultiPolygon::class,
                'geometrycollection' => GeometryCollection::class,
            ];
            foreach ($geometries as $type => $class) {
                if (!Type::hasType($type)) {
                    Type::addType($type, $class);
                }
            }
        }
    }
}
