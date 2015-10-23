<?php
/**
 * Exception for no records in database
 *
 * @package Ibonly\PotatoORM\EmptyDatabaseException
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use Exception;

class EmptyDatabaseException extends Exception
{
    public function __construct()
    {
        parent::__construct("No record found in database");
    }

    /**
     * Get the error message
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Error: " . $this->getMessage();
    }
}