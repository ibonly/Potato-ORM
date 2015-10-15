<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DBConfig;
use PDO;

class DatabaseQuery extends DBConfig
{

    public function checkTableName($table)
    {
        $output = "";
        $con = DBConfig::connect();
        $query = $con->query("select 1 from $table LIMIT 1");
        if($query !== false)
        {
            return $table;
        }
    }
}