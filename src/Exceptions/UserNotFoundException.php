<?php
/**
 * Exception for user not found
 *
 * @package Ibonly\PotatoORM\UserNotFoundException
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Data not found");
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