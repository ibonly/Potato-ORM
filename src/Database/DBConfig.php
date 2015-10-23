<?php
/**
 * This class handles database connection.
 *
 * @package Ibonly\SugarORM\DBConfig
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Ibonly\SugarORM\Inflector;
use Ibonly\SugarORM\InvalidConnectionException;

class DBConfig extends PDO
{
    /**
     * Define the database connection
     */
    public function __construct()
    {
        $dbConn = "";
        $this->loadEnv();
        $driver = getenv('DATABASE_DRIVER');
        $host = getenv('DATABASE_HOST');
        $dbname = getenv('DATABASE_NAME');
        $port= getenv('DATABASE_PORT');
        $user = getenv('DATABASE_USER');
        $password = getenv('DATABASE_PASSWORD');

        try
        {
            if ($driver === 'pgsql')
            {
                $dbConn = parent::__construct($driver . ':host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';user=' . $user . ';password=' . $password);
                $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbConn->setAttribute(PDO::ATTR_PERSISTENT, false);
            }
            elseif ($driver === 'mysql')
            {
                $dbConn = parent::__construct($driver . ':host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4', $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_PERSISTENT => false]);
            }
        } catch (InvalidConnectionException $e) {
            return $e->errorMessage();
        }
        // return $dbConn;
    }

    /**
     * Load Dotenv to grant getenv() access to environment variables in .env file
     */
    protected function loadEnv()
    {
        $dotenv = new Dotenv(__DIR__ . "../../../");
        $dotenv->load();
    }
}