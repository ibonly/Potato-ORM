<?php
/**
 * This class handles building sql query statement and check
 * that the table exist in the database.
 *
 * @package Ibonly\SugarORM\DatabaseQuery
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use PDO;
use Ibonly\SugarORM\DBConfig;
use Ibonly\SugarORM\DatabaseQueryInterface;

class DatabaseQuery extends DBConfig implements DatabaseQueryInterface
{
    /**
     * @param sanitize function
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
     * @param  [string] $table
     * @return [bool] table name
     */
    public function checkTableExist($table)
    {
        $output = "";
        $con = DBConfig::connect();
        $query = $con->query("select 1 from $table LIMIT 1");
        if($query !== false)
        {
            return true;
        }
    }

    /**
     * checkTableName
     *
     * @param  [string] $table
     * @return [string] table name
     */
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

    /**
     * selectQuery
     *
     * @param  [string] $tableName
     * @param  [string/NULL] $field
     * @param  [string/NULL] $value
     *
     * @return [string] SQL select query statement
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
     * @param  [string] $tableName
     * @param  (array)$this [variables set outside the class]
     * @return [string] SQL insert query statement
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
     * @param  [string] $tableName
     * @param  (array)$this [variables set outside the class]
     * @return [string] SQL update query statement
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