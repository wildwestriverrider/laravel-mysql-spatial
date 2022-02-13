<?php

namespace Wildwestriverrider\LaravelMysqlSpatial\Connectors;

use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Connectors\ConnectionFactory as IlluminateConnectionFactory;
use PDO;

class ConnectionFactory extends IlluminateConnectionFactory
{
    /**
     * @param string       $driver
     * @param \Closure|PDO $connection
     * @param string       $database
     * @param string       $prefix
     * @param array        $config
     *
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
