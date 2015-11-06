<?php

namespace Ibonly\PotatoORM\Test;

use PDO;
use Mockery;
use Ibonly\PotatoORM\Model;
use PHPUnit_Framework_TestCase;

class ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Define class initialization
     */
    public function setUp()
    {
        $this->databaseQuery = new Model;
    }

    /**
     * Tear down all mock objects
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * testGetTableNameException
     * Test if Exception is returned
     */
    public function testGetTableNameException()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('checkTableName')->with('SELECT 1 FROM users')->andReturn('users');

        $this->setExpectedException('\Ibonly\PotatoORM\TableDoesNotExistException');
        $this->assertTrue($this->databaseQuery->getTableName($dbConnMocked));
    }

}