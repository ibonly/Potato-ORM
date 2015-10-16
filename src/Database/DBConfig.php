<?php
/**
 * This class handles building sql query statement and check
 * that the table exist in the database.
 *
 * @package Ibonly\SugarORM\DBConfig
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Ibonly\SugarORM\InvalidConnectionException;

class DBConfig
{


    public function connect(){
        $this->loadEnv();

        $driver = getenv('DATABASE_DRIVER');
        $host = getenv('DATABASE_HOST');
        $name = getenv('DATABASE_NAME');
        $user = getenv('DATABASE_USER');
        $password = getenv('DATABASE_PASSWORD');

        // Set DSN
        $dsn = $driver.':host='.$host.';dbname='.$name;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try
        {
            $dbh = new PDO($dsn, $user, $password, $options);
        }
        // Catch any errors
        catch(PDOException $e)
        {
            echo "Error connecting to database";
        }
        return $dbh;
    }

    /**
     * Get the environment variables from .env using vlucas package
     *
     * @return String
     */
    public function loadEnv()
    {
        $dotenv = new Dotenv(__DIR__ ."../../../");
        $dotenv->load();
    }
}