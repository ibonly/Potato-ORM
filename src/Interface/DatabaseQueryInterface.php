<?php
/**
 * Defining Interface for class DatabaseQueryInterface.
 *
 * @package Ibonly\PotatoORM\DatabaseQueryInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

interface DatabaseQueryInterface
{
    public function tableName();
    
    public function fields();

    public static function selectAllQuery($tableName, $field);

    public static function whereAndClause($tableName, $data, $condition);

    public static function selectQuery($tableName, $fields, $data, $condition, $connection);

    public function insertQuery($tableName);

    public function updateQuery($tableName);

    public function query($query, $dbConnection);
}
