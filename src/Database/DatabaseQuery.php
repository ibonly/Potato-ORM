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

use PDOException;
use Ibonly\PotatoORM\DatabaseQueryInterface;
use Ibonly\PotatoORM\ColumnNotExistExeption;

class DatabaseQuery implements DatabaseQueryInterface
{
    /**
     * connect Setup database connection
     *
     * @return [type] [description]
     */
    public function connect()
    {
        return new DBConfig();
    }

    /**
     * sanitize(argument) Removes unwanted characters
     *
     * @param  $value
     *
     * @return  string
     */
    public function sanitize($value)
    {
        $value = trim($value);
        $value = htmlentities($value);
        return $value;
    }

    /**
     * checkConnection
     *
     * @param  $con
     *
     * @return [string]
     */
    public function checkConnection($con)
    {
        if( is_null($con))
            $con = self::connect();
        return $con;
    }

    /**
     * checkTableExist Check if table already in the database
     *
     * @param  $tablename
     * @param  $con
     *
     * @return bool
     */
    public function checkTableExist($table, $con=NULL)
    {
        $connection = $this->checkConnection($con);
        $query = $connection->query("SELECT 1 FROM {$table} LIMIT 1");
        if($query !== false)
            return true;
    }

    /**
     * checkTableName Return the table name
     *
     * @param  $tablename
     * @param  $con
     *
     * @return string
     */
    public function checkTableName($tableName, $con=NULL)
    {
        $connection = self::checkConnection($con);
        $query = $connection->query("SELECT 1 FROM {$tableName} LIMIT 1");
        if($query !== false)
            return $tableName;
    }

    /**
     * checkColumn Check if column exist in table
     *
     * @param  $tableName
     * @param  $columnName
     * @param  $con
     *
     * @return string
     */
    public function checkColumn($tableName, $columnName, $con=NULL)
    {
        $connection = self::checkConnection($con);
        try
        {
            $result = $connection->query("SHOW COLUMNS FROM {$tableName} LIKE '{$columnName}'");
            if ( ! $result->rowCount())
                throw new ColumnNotExistExeption();
            return $columnName;
        } catch ( ColumnNotExistExeption $e ) {
            return $e->errorMessage();
        }
    }

    /**
     * buildColumn  Build the column name
     *
     * @param  $data [description]
     *
     * @return string
     */
    public function buildColumn($data)
    {
        $counter = 0;
        $insertQuery = "";
        $arraySize = sizeof($data);

        foreach ($data as $key => $value)
        {
            $counter++;
            $insertQuery .= self::sanitize($key);
            if($arraySize > $counter)
                $insertQuery .= ", ";
        }
        return $insertQuery;
    }

    /**
     * buildValues  Build the column values
     *
     * @param  $data [description]
     *
     * @return string
     */
    public function buildValues($data)
    {
        $counter = 0;
        $insertQuery = "";
        $arraySize = sizeof($data);

        foreach ($data as $key => $value)
        {
            $counter++;
            $insertQuery .= "'".self::sanitize($value) ."'";
            if($arraySize > $counter)
                $insertQuery .= ", ";
        }
        return $insertQuery;
    }

    /**
     * buildClause  Build the clause value
     *
     * @param  $data [description]
     *
     * @return string
     */
    public function buildClause($data)
    {
        $counter = 0;
        $updateQuery = "";
        $arraySize = sizeof($data);

        foreach ($data as $key => $value)
        {
            $counter++;
            $updateQuery .= $key ." = '".self::sanitize($value)."'";
            if($arraySize > $counter)
                $updateQuery .= ", ";
        }
        return $updateQuery;
    }

    /**
     * selectQuery
     *
     * @return string
     */
    public function selectQuery($tableName, $field = NULL, $value = NULL)
    {
        $query = "";
        if ( ! is_null ($field))
            try
            {
                $columnName = self::checkColumn($tableName, self::sanitize($field));
                $query =  "SELECT * FROM $tableName WHERE $columnName = ".self::sanitize($value);
            } catch ( PDOException $e ) {
                return $e->getMessage();
            }
        else
        {
            $query = "SELECT * FROM {$tableName}";
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
        $data = ( array )$this;
        array_shift($data);

        $columnNames = $this->buildColumn($data);
        $values = $this->buildValues($data);
        $insertQuery = "INSERT INTO $tableName ({$columnNames}) VALUES ({$values})";

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
        $data = ( array ) $this;
        $data = array_slice ($data, 2);

        $values = $this->buildClause($data);
        $updateQuery = "UPDATE $tableName SET {$values} WHERE id = ". self::sanitize($this->id);

        return $updateQuery;
    }
}