<?php
/**
 * Exception for user not found
 *
 * @package Ibonly\PotatoORM\ColumnNotExistExeption
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use Exception;

class ColumnNotExistExeption extends Exception
{
    public function __construct()
    {
        parent::__construct("Column name does not exist");
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Error: " . $this->getMessage();
    }
}