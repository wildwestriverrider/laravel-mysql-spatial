<?php

namespace Wildwestriverrider\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Query\Expression;

class SpatialExpression extends Expression
{
    public function getValue(\Illuminate\Database\Grammar $grammar): string
    {
        return "ST_GeomFromText(?, ?, 'axis-order=long-lat')";
        // return "ST_GeomFromText(?, ?)";
    }

    public function getSpatialValue()
    {
        return $this->value->toWkt();
    }

    public function getSrid()
    {
        return $this->value->getSrid();
    }
}
