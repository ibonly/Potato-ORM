<?php

namespace Ibonly\PotatoORM\Test;

use PDO;
use Mockery;
use Ibonly\PotatoORM\Model;
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

    /**
     * testGetClassName
     * Test if string is returned
     */
    public function testGetClassName()
    {
        $this->assertInternalType("string", $this->databaseQuery->getClassName());
    }

    /**
     * testStripclassName
     * Test if string is returned
     */
    public function testStripclassName()
    {
        $this->assertInternalType("string", $this->databaseQuery->stripclassName());
    }

    /**
     * testGetAll
     * Test the getAll() method
     */
    public function testGetAll()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('prepare')->with('SELECT * FROM users')->andReturn($statement);

        $this->assertNull($this->databaseQuery->getAll($dbConnMocked));
    }

}