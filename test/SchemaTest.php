<?php

namespace Ibonly\PotatoORM\Test;

use PDO;
use Mockery;
use Ibonly\PotatoORM\Schema;

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Define class initialization
     */
    public function setUp()
    {
        $this->schema = new Schema();
    }

    /**
     * testIncrements
     * Test if AUTO_INCREMENT is present in returned data
     */
    public function testIncrements()
    {
        $this->assertContains('AUTO_INCREMENT', $this->schema->increments('id'));
    }

    /**
     * testStrings
     * Test if varchar is present in returned data
     */
    public function testStrings()
    {
        $this->assertContains('varchar', $this->schema->strings('name', 20));
    }

    /**
     * testText
     * Test if text is present in returned data
     */
    public function testText()
    {
        $this->assertContains('text', $this->schema->text('id'));
    }

    /**
     * testInteger
     * Test if int is present in returned data
     */
    public function testInteger()
    {
        $this->assertContains('int', $this->schema->integer('age', 5));
    }

    /**
     * testPrimaryKey
     * Test if KEY is present in returned data
     */
    public function testPrimaryKey()
    {
        $this->assertContains('KEY', $this->schema->primaryKey('id'));
    }

    /**
     * testUnique
     * Test if UNIQUE is present in returned data
     */
    public function testUnique()
    {
        $this->assertContains('UNIQUE', $this->schema->unique('email'));
    }

    /**
     * testForeignKey
     * Test if FOREIGN is present in returned data
     */
    public function testForeignKey()
    {
        $this->assertContains('FOREIGN', $this->schema->foreignKey('id', 'user-id'));
    }

    /**
     * testBuildQuery
     * Test if string is present in returned data
     */
    public function testBuildQuery()
    {
        $this->assertInternalType("string", $this->schema->buildQuery('schools'));
    }

    /**
     * testSanitizeQuery
     * Test if ); is present in returned data
     */
    public function testSanitizeQuery()
    {
        $this->assertContains(');', $this->schema->sanitizeQuery('email'));
    }
}