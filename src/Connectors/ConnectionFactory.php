<?php

namespace Wildwestriverrider\LaravelMysqlSpatial\Connectors;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Connectors\ConnectionFactory as IlluminateConnectionFactory;
use PDO;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;

class ConnectionFactory extends IlluminateConnectionFactory
{
    /**
     * @param  string  $driver
     * @param  \Closure|PDO  $connection
     * @param  string  $database
     * @param  string  $prefix
     * @return ConnectionInterface
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = []): MysqlConnection|ConnectionInterface
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);    // @codeCoverageIgnore
        }

        if ($driver === 'mysql') {
            return new MysqlConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
