<?php

namespace Ibonly\SugarORM\Test;

use Mockery;
use Ibonly\SugarORM\Model;
use PDO;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    // public function setUp()
    // {
    //     $this->model = new Model();
    // }

    private $pdo;
    public function setUp()
    {
        $this->pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE users (what VARCHAR(50) NOT NULL)");
    }
    public function tearDown()
    {
        $this->pdo->query("DROP TABLE users");
    }
    public function testClassNameIsString()
    {
        $this->assertInternalType("string", Model::stripclassName());
    }

    public function testGetAll()
    {
        $dbConnMocked = Mockery::mock('Ibonly\SugarORM\DatabaseQuery');
        $statement = Mockery::mock('\PDOStatement');

        // $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM dogs LIMIT 1')->andReturn($statement);
        $dbConnMocked->shouldReceive('query')->with('SELECT 1 FROM users LIMIT 1')->andReturn(true);

        // $this->assertTrue(Backbone::checkForTable('dogs', $dbConnMocked));
        $this->assertTrue(Model::checkTableExist('children'));
    }

}