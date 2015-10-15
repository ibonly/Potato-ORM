<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DatabaseQuery;
use Ibonly\SugarORM\SaveUserExistException;
use PDO;
use PDOException;

class Model extends DatabaseQuery
{
    protected $connect;

    public function __construct()
    {
        $this->connect =  DatabaseQuery::connect();
    }
    public function getDBConnect()
    {
        return $this->connect;
    }
    public function stripclassName()
    {
        $className = strtolower(get_called_class());
        $r = explode("\\", $className);
        return $r[2];
    }
    public function getTableName()
    {
        return DatabaseQuery::checkTableName(self::stripclassName());
    }

    public function getALL()
    {
            $query = $this->getDBConnect()->prepare('SELECT * FROM ' . self::getTableName());
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll(PDO::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                return false;
            }
    }

    public function where($field, $value)
    {
            $query = $this->getDBConnect()
                          ->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE '.$field.' = '.$value);
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll(PDO::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                return false;
            }
    }

    public function save()
    {

            $t = (array)$this;
            array_shift($t);
            $r = "";
            $arraySize = sizeof($t);
            $i = 0;
            $query = "INSERT INTO ". self::getTableName(). "(";
            foreach ($t as $key => $value) {
                $i++;
                $query .= $key;
                if($arraySize > $i)
                    $query .= ", ";
            }
            $i = 0;
            $query .= ") VALUES (";
            foreach ($t as $key => $value) {
                 $i++;
                $query .= "'".$value ."'";
                if($arraySize > $i)
                    $query .= ", ";
            }
            $query .= ")";

            try{
                $connection = $this->getDBConnect();
                $statement = $connection->prepare($query);
                $statement->execute();
                return true;
        }catch(PDOException $e){
            throw new  SaveUserExistException($e->getMessage());
        }

    }

}