<?php

namespace Ibonly\PotatoORM\Test;

use PDO;
use Mockery;
use PHPUnit_Framework_TestCase;
use Ibonly\PotatoORM\DatabaseQuery;

class DatabaseQueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Define class initialization
     */
    public function setUp()
    {
        $this->databaseQuery = new DatabaseQuery;
    }

    /**
     * Tear down all mock objects
     */
    public function tearDown()
    {
        Mockery::close();
    }

    public function testTableExist()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn($statement);

        $this->assertTrue($this->databaseQuery->checkTableExist('users', $dbConnMocked));
    }

    public function testSelectQuery()
    {
        $this->assertInternalType("string", $this->databaseQuery->selectQuery('users'));
    }

}