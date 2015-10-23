<?php
/**
 * This class handles building sql query statement and check
 * that the table exist in the database.
 *
 * @package Ibonly\PotatoORM\DatabaseQuery
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use Ibonly\PotatoORM\DatabaseQueryInterface;

class DatabaseQuery implements DatabaseQueryInterface
{
    public function connect()
    {
        return new DBConfig();
    }

    /**
     * sanitize(argument) Removes unwanted characters
     */
    public function sanitize($value)
    {
        $value = trim($value);
        $value = htmlentities($value);
        return $value;
    }

    /**
     * checkTableExist
     *
     * @return bool
     */
    public function checkTableExist($table, $con=NULL)
    {
        if( is_null($con))
        {
            $con = new DBConfig;
        }
        $query = $con->query("SELECT 1 FROM {$table} LIMIT 1");
        if($query !== false)
        {
            return true;
        }
    }

    /**
     * checkTableName
     *
     * @return string
     */
    public function checkTableName($table, $con=NULL)
    {
        if( is_null($con))
        {
            $con = new DBConfig;
        }
        $query = $con->query("SELECT 1 FROM {$table} LIMIT 1");
        if($query !== false)
        {
            return $table;
        }
    }

    /**
     * selectQuery
     *
     * @return string
     */
    public function selectQuery($tableName, $field = NULL, $value = NULL)
    {
        $query = "";
        if( is_null ($field)){
            $query = "SELECT * FROM $tableName";
        }else{
            $query =  "SELECT * FROM $tableName WHERE ".self::sanitize($field)." = ".self::sanitize($value);
        }

        return $query;
    }

    /**
     * insertQuery
     *
     * @return string
     */
    public function insertQuery($tableName)
    {
        $data = (array)$this;
        array_shift($data);
        $arraySize = sizeof($data);
        $counter = 0;
        $insertQuery = "INSERT INTO $tableName (";
        // build the column names
        foreach ($data as $key => $value)
        {
            $counter++;
            $insertQuery .= self::sanitize($key);
            if($arraySize > $counter)
                $insertQuery .= ", ";
        }

        $counter = 0;
        $insertQuery .= ") VALUES (";
        // build the values
        foreach ($data as $key => $value)
        {
             $counter++;
            $insertQuery .= "'".self::sanitize($value) ."'";
            if($arraySize > $counter)
                $insertQuery .= ", ";
        }

        $insertQuery .= ")";

        return $insertQuery;
    }

    /**
     * updateQuery
     *
     * @return string
     */
    public function updateQuery($tableName)
    {
        $counter = 0;
        $data = (array)$this;
        array_shift($data);
        array_shift($data);
        $arraySize = sizeof($data);
        $updateQuery = "UPDATE $tableName SET ";
        // build the update field and value
        foreach ($data as $key => $value)
        {
            $counter++;
            $updateQuery .= $key ." = '".self::sanitize($value)."'";
            if($arraySize > $counter)
                $updateQuery .= ", ";
        }

        $updateQuery .= " WHERE id = ". self::sanitize($this->id);

        return $updateQuery;
    }
}