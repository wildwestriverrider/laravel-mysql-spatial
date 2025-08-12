<?php

namespace Schema;

use BaseTestCase;
use Mockery;
use Wildwestriverrider\LaravelMysqlSpatial\MysqlConnection;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Blueprint;
use Wildwestriverrider\LaravelMysqlSpatial\Schema\Builder;

class BuilderTest extends BaseTestCase
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

    public function test_returns_correct_blueprint()
    {
        $connection = Mockery::mock(MysqlConnection::class);
        $grammar = Mockery::mock(\Wildwestriverrider\LaravelMysqlSpatial\Schema\Grammars\MySqlGrammar::class);
        $connection->shouldReceive('getSchemaGrammar')->times(2)->andReturn($grammar);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();

        $blueprint = $mock->createBlueprint('test', function () {});

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
