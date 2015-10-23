<?php

namespace Ibonly\SugarORM\Test;

use PDO;
use Mockery;
use Ibonly\SugarORM\Schema;

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->schema = new Schema();
    }
    public function testIncrements()
    {
        $this->assertContains('AUTO_INCREMENT', $this->schema->increments('id'));
    }
    public function testStrings()
    {
        $this->assertContains('varchar', $this->schema->strings('name', 20));
    }
    public function testText()
    {
        $this->assertContains('text', $this->schema->text('id'));
    }
    public function testInteger()
    {
        $this->assertContains('int', $this->schema->integer('age', 5));
    }
    public function testPrimaryKey()
    {
        $this->assertContains('KEY', $this->schema->primaryKey('id'));
    }
    public function testUnique()
    {
        $this->assertContains('UNIQUE', $this->schema->unique('email'));
    }
    public function testForeignKey()
    {
        $this->assertContains('FOREIGN', $this->schema->foreignKey('id', 'user_id'));
    }
    public function testBuildQuery()
    {
        $this->assertInternalType("string", $this->schema->buildQuery('schools'));
    }
    public function testSanitizeQuery()
    {
        $this->assertContains(');', $this->schema->sanitizeQuery('email'));
    }

    public function testCreateTable()
    {
        $dbConnMocked = Mockery::mock('\Ibonly\SugarORM\DBConfig');
        $statement = Mockery::mock('\PDOStatement');

        $dbConnMocked->shouldReceive('query')->with("CREATE TABLE IF NOT EXISTS users( id int(11) NOT NULL AUTO_INCREMENT );")->andReturn($statement);

        $this->setExpectedException('\InvalidArgumentException');
        $this->assertTrue($this->schema->createTable('users', $dbConnMocked));
    }
}