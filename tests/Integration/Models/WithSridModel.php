<?php

use Wildwestriverrider\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithSridModel.
 *
 * @property int                                          id
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\Point      location
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\LineString line
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\LineString shape
 */
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}
