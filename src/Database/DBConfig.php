<?php
/**
 * This class handles database connection.
 *
 * @package Ibonly\PotatoORM\DBConfig
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Ibonly\PotatoORM\Inflector;

class DBConfig extends PDO
{
    protected $driver;
    protected $host;
    protected $dbname;
    protected $port;
    protected $user;
    protected $password;
    protected $sqlitePath;

    /**
     * Define the database connection
     */
    public function __construct()
    {
        $this->loadEnv();
        $this->driver     = getenv('DATABASE_DRIVER');
        $this->host       = getenv('DATABASE_HOST');
        $this->dbname     = getenv('DATABASE_NAME');
        $this->port       = getenv('DATABASE_PORT');
        $this->user       = getenv('DATABASE_USER');
        $this->password   = getenv('DATABASE_PASSWORD');
        $this->sqlitePath = getenv('SQLITE_PATH');
        $this->setUp();
    }
    /**
     * pgsqlConnectionString Postgres connection string
     *
     * @return string
     */
    protected function pgsqlConnectionString()
    {
        return $this->driver . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';user=' . $this->user . ';password=' . $this->password;
    }
   
    private function setUp()
    {
        try
        {
         $this->setDriver();   
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
    
    /**
     * Sets the database driver
     */
    
    private function setDriver()
    {
        if ($this->driver === 'pgsql' || $this->driver === 'postgres')
            {
                parent::__construct($this->pgsqlConnectionString());
            }
            elseif ($this->driver === 'mysql')
            {
                parent::__construct($this->mysqlConnectionString(), $this->user, $this->password);
            }
            elseif($this->driver === 'sqlite')
            {
                parent::__construct($this->sqlitConnectionString());
            }
    }
    
    /**
     * mysqlConnectionString Mysql connection string
     *
     * @return string
     */
    
    protected function mysqlConnectionString()
    {
        return $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
    }

    /**
     * sqliteConnectionString Sqlite connection string
     *
     * @return string
     */
    protected function sqlitConnectionString()
    {
        return $this->driver . ':' . $this->sqlitePath;
    }

    /**
     * Load Dotenv to grant getenv() access to environment variables in .env file
     */
    protected function loadEnv()
    {
        if( ! getenv("APP_ENV"))
        {
            $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
            $dotenv->load();
        }
    }
}
