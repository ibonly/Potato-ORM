<?php
/**
 * PotatoORM manages the persistence of database CRUD operations.
 *
 * @package Ibonly\PotatoORM\Model
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use Ibonly\PotatoORM\DatabaseQuery;
use Ibonly\PotatoORM\RelationshipsInterface;

class Relationships extends DatabaseQuery implements RelationshipsInterface
{
    public function joinClause($con = null)
    {
        $connection = self::checkConnection($con);

        $data = self::query('SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME="'.self::getTableName($connection).'"')->all();

        array_shift($data);
        $output = "";
        $i = 0;

        if ($data[0]->REFERENCED_TABLE_NAME !== null) {
			$arraySize  = count($data);

	        foreach($data as $key => $value) {
	        	$output .= ' JOIN '.$value->REFERENCED_TABLE_NAME;
	        }
	        foreach($data as $key => $value) {
	        	$i++;
	        	$whereAnd = $i > 1 ? 'AND' : 'WHERE';
	        	$output .= ' '.$whereAnd.' '.self::getTableName($connection).'.'.$value->COLUMN_NAME.'='.$value->REFERENCED_TABLE_NAME.'.'.$value->REFERENCED_COLUMN_NAME.' ';
	        }
	     } else {
	     	$output = false;
	     }
        return $output;
    }

    public function whereClause($data, $condition = null, $con = null)
    {
    	$joinClause = self::joinClause();
        $connection = self::checkConnection($con);
        $tableName  = self::getTableName($connection);
        $columnName = self::whereAndClause($tableName, $data, $condition);

    	$query = 'SELECT * FROM '.$tableName;

    	if (! $joinClause) {
    		$query .= ' WHERE '.$columnName;
    	} else {
    		$query .= $joinClause .' AND '.$columnName;
    	}
    	return $query;
    }
}