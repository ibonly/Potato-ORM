<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DBConfig;
use PDO;

class DatabaseQuery extends DBConfig
{

    public function checkTableName($table)
    {
        $output = "";
        $query = $this->dbh->query("select 1 from $table LIMIT 1");
        if($query !== false)
        {
            return true;
        }
    }
}