<?php

namespace Ibonly\SugarORM\Test;

use PDO;
use Mockery;
use Ibonly\SugarORM\Model;
use PHPUnit_Framework_TestCase;

class ModelTest extends PHPUnit_Framework_TestCase
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

    public function testGetClassName()
    {
        $this->assertInternalType("string", $this->databaseQuery->getClassName());
    }

    public function testStripclassName()
    {
        $this->assertInternalType("string", $this->databaseQuery->stripclassName());
    }


    public function testGetTableNameException()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\SugarORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn($statement);

        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->getTableName());
    }
}