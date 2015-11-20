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
    public function checkTableExist($table, $con=NULL);

    public static function checkTableName($table, $dbConnection=NULL);

    public static function selectQuery($tableName, $field = NULL, $value = NULL, $connection);

    public function insertQuery($tableName);

    public function updateQuery($tableName);
}