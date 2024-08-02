<?php

namespace Wildwestriverrider\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use JetBrains\PhpStorm\Pure;
use Wildwestriverrider\LaravelMysqlSpatial\Types\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values): int
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    #[Pure]
    protected function asWKT(GeometryInterface $geometry): SpatialExpression
    {
        return new SpatialExpression($geometry);
    }
}
