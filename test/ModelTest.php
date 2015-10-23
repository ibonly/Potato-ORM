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
        $dbConnMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn($statement);

        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->getTableName($dbConnMocked));
    }

    public function testGetAll()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->getAll());
    }

    public function testWhere()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->where('id', 1));
    }

    public function testFind()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->find(1));
    }

    public function testSave()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->save());
    }

    public function testDestroy()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->destroy(1));
    }
}