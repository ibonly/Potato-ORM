<?php

namespace Ibonly\PotatoORM\Test;

use PDO;
use Mockery;
use Ibonly\PotatoORM\Model;
use Ibonly\PotatoORM\DBConfig;
use PHPUnit_Framework_TestCase;
use Ibonly\PotatoORM\Test\Stub\StubTest;

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $dbConnectionMocked;
    protected $statement;

    /**
     * Define class initialization
     */
    public function setUp()
    {
        $this->dbConnectionMocked = Mockery::mock('\Ibonly\PotatoORM\DBConfig');
        $this->statement = Mockery::mock('\PDOStatement');

        $this->dbConnectionMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn(false);
        $this->dbConnectionMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn($this->statement);
    }

    /**
     * Tear down all mock objects
     */
    public function tearDown()
    {
        Mockery::close();
    }

    public function getModelClass()
    {
        return new Model;
    }

    /**
     * testGetClassName
     * Test if string is returned
     */
    public function testGetClassName()
    {
        $this->assertInternalType("string", $this->getModelClass()->getClassName());
    }

    /**
     * testStripclassName
     * Test if string is returned
     */
    public function testStripclassName()
    {
        $this->assertInternalType("string", $this->getModelClass()->stripclassName());
    }

    /**
     * testGetAll
     * Test the getAll() method
     */
    public function testWhere()
    {
        $this->dbConnectionMocked->shouldReceive('prepare')->with('SELECT * FROM users WHERE id = 1')->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->with([2]);
        $this->statement->shouldReceive('rowCount')->andReturn(1);
        $this->statement->shouldReceive('fetch')->with(DBConfig::FETCH_ASSOC)->andReturn(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com']);
        $this->assertNull(StubTest::where('id', 1, $this->dbConnectionMocked));
    }
    /**
     * Test method getAll of Model class
     */
    public function testGetAll()
    {
        $this->dbConnectionMocked->shouldReceive('prepare')->with('SELECT * FROM users')->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('rowCount')->andReturn(1);
        $this->statement->shouldReceive('fetchAll')->with(DBConfig::FETCH_ASSOC)->andReturn(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com']);

        $this->assertNull(StubTest::getAll($this->dbConnectionMocked));
    }


    /**
     * Test method find of Model class
     */
    public function testFind()
    {
        $this->dbConnectionMocked->shouldReceive('prepare')->with('SELECT * FROM users WHERE id = 1')->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->with([1]);
        $this->statement->shouldReceive('rowCount')->andReturn(1);
        $this->statement->shouldReceive('fetchAll')->with(DBConfig::FETCH_ASSOC)->andReturn(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com']);
        // $this->assertInstanceOf('Ibonly\PotatoORM\Test\Stub\StubTest', StubTest::find(1, $this->dbConnectionMocked));
        $this->assertNull(StubTest::find(1, $this->dbConnectionMocked));
    }

}