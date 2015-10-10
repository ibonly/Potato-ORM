<?php

namespace Ibonly\SugarORM;
use PDO;

class DBConfig
{

    private $host      = "localhost";
    private $user      = "root";
    private $pass      = "";
    private $dbname    = "newsletter";

    protected $dbh;
    protected $error;

    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try
        {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e)
        {
            $this->error = $e->getMessage();
        }
    }
}