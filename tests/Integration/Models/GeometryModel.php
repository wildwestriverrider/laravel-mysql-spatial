<?php

use Illuminate\Database\Eloquent\Model;
use Wildwestriverrider\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * Class GeometryModel.
 *
 * @property int                                          id
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\Point      location
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\LineString line
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}
