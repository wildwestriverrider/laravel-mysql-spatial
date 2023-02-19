<?php

use Illuminate\Database\Eloquent\Model;
use Wildwestriverrider\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * Class NoSpatialFieldsModel.
 *
 * @property \Wildwestriverrider\LaravelMysqlSpatial\Types\Geometry geometry
 */
class NoSpatialFieldsModel extends Model
{
    use SpatialTrait;

    protected $table = 'no_spatial_fields';

    public $timestamps = false;
}
