<?php

namespace Ibonly\SugarORM\Test;

use PHPUnit_Framework_TestCase;
use Ibonly\SugarORM\Model;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->model = new Model();
    }

    public function testClassNameIsString()
    {
        $this->assertInternalType("string", $this->model->getClassName());
    }
}