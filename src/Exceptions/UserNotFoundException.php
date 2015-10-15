<?php
/**
 * Exception for user already exist
 *
 * @package Ibonly\SugarORM\UserAlreadyExistException
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use Exception;

class UserNotFoundException extends Exception
{



    public function __construct()
    {
        parent::__construct("User not found");
    }

    /**
     * Handle empty user
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Error: " . $this->getMessage();
    }
}