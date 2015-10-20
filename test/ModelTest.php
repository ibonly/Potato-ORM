<?php

namespace Ibonly\SugarORM\Test;

use PHPUnit_Framework_TestCase;
use Ibonly\SugarORM\Model;
use PDO;

class ModelTest extends PHPUnit_Framework_TestCase
{
    // public function setUp()
    // {
    //     $this->model = new Model();
    // }

    public function testClassNameIsString()
    {
        $this->assertInternalType("string", Model::stripclassName());
    }
    public function testGetAll()
    {
        // $this->setExpectedException('\Ibonly\SugarORM\EmptyDatabaseException');
        // Model::getAll();
    }

}