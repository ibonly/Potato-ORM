<?php

namespace Ibonly\SugarORM;
use PDO;
use PDOException;
use Ibonly\SugarORM\InvalidConnectionException;

class DBConfig
{


    public function connect(){
        // Set DSN
        $dsn = 'mysql:host=localhost;dbname=sugarorm';
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try
        {
            $dbh = new PDO($dsn, "root", "", $options);
        }
        // Catch any errors
        catch(PDOException $e)
        {
            echo "Error connecting to database";
        }
        return $dbh;
    }
}