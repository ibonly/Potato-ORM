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
use Ibonly\PotatoORM\DataNotFoundException;
use Ibonly\PotatoORM\DatabaseQueryInterface;
use Ibonly\PotatoORM\ColumnNotExistExeption;
use Ibonly\PotatoORM\InvalidConnectionException;
use Ibonly\PotatoORM\TableDoesNotExistException;

class DatabaseQuery implements DatabaseQueryInterface
{
    //Inject the inflector trait, file upload trait
    use Inflector, Upload;

    protected $properties = [];
    protected $output;

    public function __set($property, $value)
    {
        $this->properties[$property] = $value;
    }

    public function __get($property)
    {
        return $this->properties[$property];
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * connect Setup database connection
     */
    protected static function connect()
    {
        return new DBConfig();
    }

    /**
     * stripclassName()
     *
     * @return string
     */
    public static function stripclassName()
    {
        $className = strtolower(get_called_class());
        $nameOfClass = explode("\\", $className);

        return end($nameOfClass);
    }

    /**
     * Get the table name if defined in the model
     * 
     * @return string
     */
    public function tableName()
    {
        return $this->table ?? null;
    }
    /**
     * getClassName()
     *
     * @return string
     */
    public function getClassName()
    {
        return ($this->tableName() === null) ? self::pluralize(self::stripclassName()) : $this->tableName();
    }

    /**
     * getTableName()
     *
     * @return string
     */
    protected function getTableName($connection)
    {
        return DatabaseQuery::checkTableName($this->getClassName(), $connection);
    }


    /**
     * sanitize(argument) Removes unwanted characters
     *
     * @param  $value
     *
     * @return  string
     */
    protected static function sanitize($value)
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
     * @return string
     */
    protected static function checkConnection($con)
    {
        return ($con === null) ? self::connect() : $con;
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
        {
            return true;
        }
    }

    /**
     * checkTableName Return the table name
     *
     * @param  $tablename
     * @param  $con
     *
     * @return string
     */
    protected static function checkTableName($tableName, $con=NULL)
    {
        $connection = self::checkConnection($con);

        $query = $connection->query("SELECT 1 FROM {$tableName} LIMIT 1");
        if($query !== false)
        {
            return $tableName;
        }
        throw new TableDoesNotExistException();
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
    protected static function checkColumn($tableName, $columnName, $con=NULL)
    {
        $connection = self::checkConnection($con);

            $result = $connection->prepare("SELECT {$columnName} FROM {$tableName}");
            $result->execute();
            if (! $result->columnCount())
            {
                throw new ColumnNotExistExeption();
            }
            return $columnName;
    }

    /**
     * buildColumn  Build the column name
     *
     * @param  $data
     *
     * @return string
     */
    protected static function buildColumn($data)
    {
        $counter = 0;
        $insertQuery = "";
        $arraySize = count($data);

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
     * @param  $data
     *
     * @return string
     */
    protected static function buildValues($data)
    {
        $counter = 0;
        $insertQuery = "";
        $arraySize = count($data);

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
     * @param  $data
     *
     * @return string
     */
    protected static function buildClause($tableName, $data)
    {
        $counter = 0;
        $updateQuery = "";
        $arraySize = count($data);

        foreach ($data as $key => $value)
        {
            $counter++;
            $columnName = self::checkColumn($tableName, self::sanitize($key));
            $updateQuery .= $columnName ." = '".self::sanitize($value)."'";
            if ($arraySize > $counter)
            {
                $updateQuery .= ", ";
            }
        }
        return $updateQuery;
    }

    /**
     * Get the fields to be fillables defined in the model
     * 
     * @return string
     */
    protected function fields()
    {
        if (isset($this->fillables)) {
            $this->output = (sizeof($this->fillables) > 0) ? implode($this->fillables, ',') : '*';
        } else {
            $this->output = '*';
        }

        return $this->output;
    }

    /**
     * selectAllQuery
     *
     * @return string
     */
    public function selectAllQuery($tableName)
    {
        return "SELECT ".$this->fields()." FROM {$tableName}";
    }

    /**
     * whereAndClause
     *
     * @return string
     */
    public static function whereAndClause($tableName, $data, $condition)
    {
        $where = "";
        $counter = 0;
        $arraySize = count($data);

        if ($data !== null) {
            foreach ($data as $key => $value)
            {
                $counter++;
                $columnName = self::checkColumn($tableName, self::sanitize($key));
                $where .= $tableName.'.'.$columnName ." = '".self::sanitize($value)."'";
                if ($arraySize > $counter)
                {
                    $where .= " " . $condition . " ";
                }
            }
        } else {
            $where = "";
        }

        return $where;
    }
    
    public static function whereLikeClause($tableName, $data, $condition)
    {
        $where = "";
        $counter = 0;
        $arraySize = count($data);

        foreach ( $data as $key => $value )
        {
            $counter++;
            $columnName = self::checkColumn($tableName, self::sanitize($key));
            $where .= $columnName ." LIKE '%".self::sanitize($value)."%'";
            if ( $arraySize > $counter )
            {
                $where .= " " . $condition . " ";
            }
        }

        return $where;
    }

    /**
     * selectQuery
     *
     * @return string
     */
    public static function selectQuery($tableName, $fields, $data, $condition, $connection)
    {
        $query = "";
        $columnName = "";
        
        try
        {
            $arraySize = count($data);
            if( $arraySize > 1 && $condition == NULL)
            {
                $query = "Please Supply the condition";
            }
            else
            {
                if ($condition === 'AND' || $condition === 'OR' || $condition === NULL) {
                    $columnName = self::whereAndClause($tableName, $data, $condition);
                } elseif ($condition === 'LIKE') {
                    $columnName = self::whereLikeClause($tableName, $data, $condition);
                } 
                $query =  "SELECT $fields FROM $tableName WHERE $columnName";
            }
        } catch (PDOException $e) {
            $query = $e->getMessage();
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
        $columnNames = self::buildColumn($this->properties);
        $values = self::buildValues($this->properties);

        return "INSERT INTO $tableName ({$columnNames}) VALUES ({$values})";
    }

    /**
     * updateQuery
     *
     * @return string
     */
    public function updateQuery($tableName, $id)
    {
        $values = self::buildClause($tableName, $this->properties);
        $updateQuery = "UPDATE $tableName SET {$values} WHERE id = $id";

        return $updateQuery;
    }

    /**
     * query($query, $dbCOnnection)
     * Raw sql query
     *
     * @return object
     */
    public function query($query, $con = NULL)
    {
        $connection = self::checkConnection($con);

        $query = $connection->prepare($query);
        $query->execute();
        if ($query->rowCount())
        {
            return new GetData($query->fetchAll($connection::FETCH_ASSOC));
        }
        throw new DataNotFoundException();
    }
}
