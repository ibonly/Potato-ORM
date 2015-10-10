<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DatabaseQuery;

class Model extends DatabaseQuery
{
    public function stripclassName()
    {
        $className = strtolower(get_called_class());
        $r = explode("\\", $className);
        return $r[2];
    }
    public function getClassName()
    {
        return $this->checkTableName($this->stripclassName());
    }



}