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
    public function connect();

    public function checkTableExist($table, $dbConnection=NULL);

    public function checkTableName($table, $dbConnection=NULL);

    public function selectQuery($tableName, $field = NULL, $value = NULL);

    public function insertQuery($tableName);

    public function updateQuery($tableName);

}