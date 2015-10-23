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
        $this->assertTrue($this->databaseQuery->getTableName($dbConnMocked));
    }

    public function testGetAll()
    {
        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->getAll());
    }

    public function testWhere()
    {
        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->where('id', 1));
    }

    public function testFind()
    {
        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->find(1));
    }

    public function testSave()
    {
        $this->setExpectedException('\Ibonly\SugarORM\SaveUserExistException');
        $this->assertTrue($this->databaseQuery->save());
    }

    public function testDestroy()
    {
        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->destroy(1));
    }
}