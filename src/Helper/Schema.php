<?php
/**
 * SugarORM\Schema manages the creation of database table.
 *
 * @package Ibonly\SugarORM\Schema
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use PDOException;
use Ibonly\SugarORM\DatabaseQuery;
use Ibonly\SugarORM\SchemaInterface;

class Schema extends DatabaseQuery implements SchemaInterface
{
    protected $fieldDescription = [];
    /**
     * field(arguments) contains the sql field statement
     *
     * @param  $type
     * @param  $fieldName
     * @param  $length
     *
     * @return [array]
     */
    public function field($type, $fieldName, $length=NULL)
    {
        if(is_null($length)){
             $this->fieldDescription[] = $type ." ".$fieldName;
        }else
        {
         $this->fieldDescription[] = $type ." ".$fieldName." ".$length;
        }

    }

    /**
     * buildQuery(argument): Builds the CREATE query
     *
     * @param  [string] $tablename
     *
     * @return [string] SQL CREATE query statmement
     */
    public function buildQuery($tablename)
    {
        $i = 0;
        $query = "CREATE TABLE IF NOT EXISTS {$tablename} (".PHP_EOL;

        $callback = function($fieldName) use (&$query)
        {
            $e = explode(" ", $fieldName);
            if(sizeof($e) == 2)
            {
                $query .= $this->$e[0]($e[1], 20) .", ".PHP_EOL;
            }else
            {
                    $query .= $this->$e[0]($e[1], $e[2]) .", ".PHP_EOL;
            }
        };
        array_walk($this->fieldDescription, $callback);
        $query .= ');';        return $query;
    }

    /**
     * sanitizeQuery(argument) Removes the unwanted character in the build
     *                         and completes the statement
     *
     * @param  [string] $query
     *
     * @return [string] Complete sql CREATE statement
     */
    public function sanitizeQuery($query)
    {
        $q = substr_replace($this->buildQuery($query), "", -6);
        $q .= ");";
        return $q;
    }

    /**
     * createTable(argument) Execute the CREATE query
     *
     * @param  [string] $tablename
     *
     * @return [bool]
     */
    public function createTable($tablename)
    {
        try
        {
            $connection = DatabaseQuery::connect();
            $sqlQuery = self::sanitizeQuery($tablename);
            $query = $connection->prepare($sqlQuery);
            if($query->execute())
            {
                return true;
            }
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    /**
     * increments(argument)
     *
     * @param  [string] $value [description]
     *
     * @return [string]
     */
    public function increments($value)
    {
        return $value." int(11) NOT NULL AUTO_INCREMENT";
    }

    /**
     * strings(arguments)
     *
     * @param  [strings] $value
     * @param  [int] $length
     *
     * @return [string]
     */
    public function strings($value, $length)
    {
        return $value ." varchar (".$length.") NOT NULL";
    }

    /**
     * text(argument)
     *
     * @param  [string] $value
     *
     * @return [string]
     */
    public function text($value)
    {
        return $value." text NOT NULL";
    }

    /**
     * increments(argument)
     *
     * @param  [string] $value [description]
     * @param  [int] $length [description]
     *
     * @return [string]
     */
    public function integer($value, $length)
    {
        return $value." int(".$length.") NOT NULL";
    }

    /**
     * increments(argument)
     *
     * @param  [string] $value [description]
     *
     * @return [string]
     */
    public function primaryKey($value)
    {
        return "PRIMARY KEY ({$value})";
    }

    /**
     * unique(argument)
     *
     * @param  [string] $value
     *
     * @return [string]
     */
    public function unique($value)
    {
        return "UNIQUE KEY {$value} ({$value})";
    }
    /**
     * foreignKey(argument)
     *
     * @param  [type] $value
     * @param  [string/int] $length
     *
     * @return [string]
     */
    public function foreignKey($value, $length)
    {
        $r = explode("_", $length);
        return "FOREIGN KEY ({$value}) REFERENCES ".$r[0]."(".$r[1].")";
    }
}