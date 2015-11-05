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
     * testGetTableNameException
     * Test if Exception is returned
     */
    public function testGetTableNameException()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn($statement);

        $this->setExpectedException('\PDOException');
        $this->assertTrue($this->databaseQuery->getTableName($dbConnMocked));
    }

    /**
     * testGetAllException
     * Test if Exception is returned
     */
    public function testGetAllException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->getAll());
    }

    /**
     * testWhereException
     * Test if Exception is returned
     */
    public function testWhereException()
    {
        $this->assertContains('Error:', $this->databaseQuery->where('id', 1));
    }

    /**
     * testFindException
     * Test if Exception is returned
     */
    public function testFindException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->find(1));
    }

    /**
     * testSaveException
     * Test if Exception is returned
     */
    public function testSaveException()
    {
        $this->setExpectedException('\Ibonly\PotatoORM\SaveUserExistException');
        $this->assertTrue($this->databaseQuery->save());
    }

    /**
     * testDestroyException
     * Test if Exception is returned
     */
    public function testDestroyException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->databaseQuery->destroy(1));
    }
}