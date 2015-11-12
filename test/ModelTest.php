<?php

namespace Ibonly\PotatoORM\Test;

use Mockery;
use Ibonly\PotatoORM\User;
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

        $this->dbConnectionMocked->shouldReceive('query')->with('SELECT 1 FROM stubtests LIMIT 1')->andReturn($this->statement);
    }

    /**
     * Tear down all mock objects
     */
    public function tearDown()
    {
        Mockery::close();
    }

    public function getStubClass()
    {
        return new StubTest;
    }

    /**
     * testGetClassName
     * Test if string is returned
     */
    public function testGetClassName()
    {
        $this->assertInternalType("string", $this->getStubClass()->getClassName());
    }

    /**
     * testStripclassName
     * Test if string is returned
     */
    public function testStripclassName()
    {
        $this->assertInternalType("string", $this->getStubClass()->stripclassName());
    }

    /**
     * testGetAll
     * Test the getAll() method
     */
    public function testWhere()
    {
        $this->dbConnectionMocked->shouldReceive('prepare')->with('SELECT id FROM stubtests')->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('columnCount')->andReturn(1);

        $this->dbConnectionMocked->shouldReceive('prepare')->with("SELECT * FROM stubtests WHERE id = '1'")->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('rowCount')->andReturn(1);
        $this->statement->shouldReceive('fetchAll')->with(DBConfig::FETCH_ASSOC)->andReturn(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com']);


        $this->assertEquals(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com'], StubTest::where('id', 1, $this->dbConnectionMocked));
    }

    /**
     * Test method getAll of Model class
     */
    public function testGetAll()
    {
        $this->dbConnectionMocked->shouldReceive('query')->with('SELECT 1 FROM modeltests LIMIT 1')->andReturn($this->statement);
        $this->dbConnectionMocked->shouldReceive('prepare')->with('SELECT * FROM modeltests')->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('rowCount')->andReturn(1);
        $this->statement->shouldReceive('fetchAll')->with(DBConfig::FETCH_ASSOC)->andReturn(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com']);

        $this->assertEquals(['id' => 1, 'username' => 'ibonly', 'email' => 'ibonly@yahoo.com'], StubTest::getAll($this->dbConnectionMocked));
    }

    public function testSaveUserAlreadyExist()
    {
        $mock = Mockery::mock('Ibonly\PotatoORM\Test\Stub\StubTest');

        $mock->username = 'james';
        $mock->email = 'johndoe@email.com';

        $this->setExpectedException('\Ibonly\PotatoORM\SaveUserExistException');
        $this->assertTrue($this->getStubClass()->save());
    }

    public function testDestroy()
    {
        $this->dbConnectionMocked->shouldReceive('query')->with('SELECT 1 FROM modeltests LIMIT 1')->andReturn($this->statement);
        $this->dbConnectionMocked->shouldReceive('prepare')->with("DELETE FROM modeltests WHERE id = 1")->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('rowCount')->andReturn(1);

        $this->assertEquals(1, StubTest::destroy(1, $this->dbConnectionMocked));
    }
}