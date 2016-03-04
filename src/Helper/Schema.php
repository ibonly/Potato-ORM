<?php
/**
 * SugarORM\Schema manages the creation of database table.
 *
 * @package Ibonly\SugarORM\Schema
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use PDOException;
use Ibonly\PotatoORM\DatabaseQuery;
use Ibonly\PotatoORM\SchemaInterface;

class Schema extends DatabaseQuery implements SchemaInterface
{
    //Inject the inflector trait
    use Inflector;

    protected $fieldDescription = [];

    /**
     * field(arguments) contains the sql field statement
     *
     * @return array
     */
    public function field($type, $fieldName, $length=NULL)
    {
        if($length === null){
             $this->fieldDescription[] = $type ." ".$fieldName;
        }else
        {
         $this->fieldDescription[] = $type ." ".$fieldName." ".$length;
        }

    }

    /**
     * buildQuery(argument): Builds the CREATE query
     *
     * @return string
     */
    public function buildQuery($tablename)
    {
        $pluralTableName = self::pluralize($tablename);
        $query = "CREATE TABLE IF NOT EXISTS {$pluralTableName} (".PHP_EOL;

        $callback = function($fieldName) use (&$query)
        {
            $e = explode(" ", $fieldName);
            if(count($e) == 2)
            {
                $query .= $this->$e[0]($e[1], 20) .", ".PHP_EOL;
            }else
            {
                    $query .= $this->$e[0]($e[1], $e[2]) .", ".PHP_EOL;
            }
        };
        array_walk($this->fieldDescription, $callback);
        $query .= ');';
        return $query;
    }

    /**
     * SanitizeQuery(argument) Removes the unwanted character in the build
     * and completes the statement
     *
     * @return string
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
     * @return bool
     */
    public function createTable($tablename, $connection = NULL)
    {
        $connection = DatabaseQuery::connect();
        try
        {
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
     * @return string
     */
    public function increments($value)
    {
        return $value." int(11) NOT NULL AUTO_INCREMENT";
    }

    /**
     * strings(arguments)
     *
     * @return string
     */
    public function strings($value, $length)
    {
        return $value ." varchar (".$length.") NOT NULL";
    }

    /**
     * text(argument)
     *
     * @return string
     */
    public function text($value)
    {
        return $value." text NOT NULL";
    }

    /**
     * increments(argument)
     *
     *
     * @return string
     */
    public function integer($value, $length)
    {
        return $value." int(".$length.") NOT NULL";
    }

    /**
     * increments(argument)
     *
     * @return string
     */
    public function primaryKey($value)
    {
        return "PRIMARY KEY ({$value})";
    }

    /**
     * unique(argument)
     *
     * @return string
     */
    public function unique($value)
    {
        return "UNIQUE KEY {$value} ({$value})";
    }

    /**
     * foreignKey(argument)
     *
     * @return string
     */
    public function foreignKey($value, $length)
    {
        $r = explode("_", $length);
        return "FOREIGN KEY ({$value}) REFERENCES ".$r[0]."(".$r[1].")";
    }

    /**
     * dateTime description]
     *
     * @param  [type] $value [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    public function dateTime($value, $type = NULL)
    {
        $apend = "";
        switch ($type) {
            case 'time':
                    $apend = 'time';
                break;
            case 'timestamp':
                    $apend = 'timestamp';
                break;
            case 'date':
                    $apend = 'date';
                break;
            case 'datetime':
                    $apend = 'datetime';
                break;
            case 'year':
                    $apend = 'year(4)';
                break;
            default:
                    $apend = 'timestamp';
                break;
        }
        return $value . " " . $apend . " NOT NULL";
    }
}