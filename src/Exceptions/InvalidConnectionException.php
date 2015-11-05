<?php
/**
 * Exception for user not found
 *
 * @package Ibonly\PotatoORM\InvalidConnectionException
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use PDOException;

class InvalidConnectionException extends PDOException
{
    public function __construct()
    {
        parent::__construct("Error connect to database!!!, Invalid connection provided");
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