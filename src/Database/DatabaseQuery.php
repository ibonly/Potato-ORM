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

    public function selectQuery($tableName, $field = NULL, $value = NULL)
    {
        $query = "";
            if( is_null ($field)){
                $query = "SELECT * FROM $tableName";
            }else{
                $query =  "SELECT * FROM $tableName WHERE ".$field." = ".$value;
            }

        return $query;
    }

    public function insertQuery($tableName)
    {
        $t = (array)$this;
        array_shift($t);
        $r = "";
        $arraySize = sizeof($t);
        $i = 0;

        $insertQuery = "INSERT INTO $tableName (";
        foreach ($t as $key => $value) {
            $i++;
            $insertQuery .= $key;
            if($arraySize > $i)
                $insertQuery .= ", ";
        }
        $i = 0;
        $insertQuery .= ") VALUES (";
        foreach ($t as $key => $value) {
             $i++;
            $insertQuery .= "'".$value ."'";
            if($arraySize > $i)
                $insertQuery .= ", ";
        }
        $insertQuery .= ")";
        return $insertQuery;
    }

    public function updateQuery($tableName)
    {
            $r = (array)$this;
            array_shift($r);
            array_shift($r);
            $arraySize = sizeof($r);
            $updateQuery = "UPDATE $tableName SET ";
            $i = 0;
            foreach ($r as $key => $value) {
                $i++;
                $updateQuery .= $key ." = '".$value."'";
                if($arraySize > $i)
                    $updateQuery .= ", ";
            }
            $updateQuery .= " WHERE id = ". $this->id;
        return $updateQuery;
    }
}