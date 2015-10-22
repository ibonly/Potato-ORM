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

class Schema extends DatabaseQuery
{
    protected $fieldDescription = [];

    public function field($type, $fieldName, $length=NULL)
    {
        if(is_null($length)){
             $this->fieldDescription[] = $type ." ".$fieldName;
        }else
        {
         $this->fieldDescription[] = $type ." ".$fieldName." ".$length;
        }

    }

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
    public function sanitizeQuery($query)
    {
        $q = substr_replace($this->buildQuery($query), "", -6);
        $q .= ");";
        return $q;
    }
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

    public function increments($value)
    {
        return $value." int(11) NOT NULL AUTO_INCREMENT";
    }
    public function string($value, $length)
    {
        return $value ." varchar (".$length.") NOT NULL";
    }
    public function text($value)
    {
        return $value." text NOT NULL";
    }
    public function integer($value, $length=NULL)
    {
        return $value." int(".$length.") NOT NULL";
    }
    public function primaryKey($value)
    {
        return "PRIMARY KEY ({$value})";
    }
    public function foreignKey($value, $length)
    {
        $r = explode("_", $length);
        return "FOREIGN KEY ({$value}) REFERENCES ".$r[0]."(".$r[1].")";
    }
}