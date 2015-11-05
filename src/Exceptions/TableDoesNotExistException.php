<?php
/**
 * Exception for user not found
 *
 * @package Ibonly\PotatoORM\TableDoesNotExistException
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use PDOException;

class TableDoesNotExistException extends PDOException
{
    public function __construct()
    {
        parent::__construct("Table does not exist in the database");
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function errorMessage()
    {
        return self::getMessage();
    }
}