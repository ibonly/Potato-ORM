<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DatabaseQuery;
use Ibonly\SugarORM\SaveUserExistException;
use Ibonly\SugarORM\UserNotFoundException;
use PDO;
use PDOException;

class Model extends DatabaseQuery
{

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
            $conn = DatabaseQuery::connect();
            $query = $conn->prepare('SELECT * FROM ' . self::getTableName());
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll(PDO::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                return false;
            }
    }

    public function where($field, $value)
    {
            $conn = DatabaseQuery::connect();
            $query = $conn->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE '.$field.' = '.$value);
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll(PDO::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                return false;
            }
    }

    public function find($value)
    {
        $conn = DatabaseQuery::connect();
        $query = $conn->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE id = '.$value);
        $query->execute();
        if ($query->rowCount()) {
            $found = new static;
            $found->id = $value;
            $found->data = $query->fetchAll($conn::FETCH_ASSOC);
            return $found;
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

            $insertQuery = "INSERT INTO ". self::getTableName(). "(";
            foreach ($t as $key => $value) {
                $i++;
                $insertQuery .= $key;
                if($arraySize > $i)
                    $insertQuery .= ", ";
            }
            $i = 0;
            $insertQuery .= ") VALUES (";
            foreach ($t as $key => $value) {
                 $i++;
                $insertQuery .= "'".$value ."'";
                if($arraySize > $i)
                    $insertQuery .= ", ";
            }
            $insertQuery .= ")";

            $r = (array)$this;
            array_shift($r);
            array_shift($r);
            $arraySize = sizeof($r);
            $updateQuery = "UPDATE ". self::getTableName(). " SET ";
            $i = 0;
            foreach ($r as $key => $value) {
                $i++;
                $updateQuery .= $key ." = '".$value."'";
                if($arraySize > $i)
                    $updateQuery .= ", ";
            }
            $updateQuery .= " WHERE id = ". $this->id;

        try{
            $connection = DatabaseQuery::connect();
                if ( ! isset ($this->id)  && ! is_array($this->data) ) {
                    $statement = $connection->prepare($insertQuery);
                    $statement->execute();
                    return true;
                }else{
                    $statement = $connection->prepare($updateQuery);
                    $statement->execute();
                    return true;
                }
        }catch(PDOException $e){
            throw new  SaveUserExistException($e->getMessage());
        }
    }

    public function destroy($value)
    {
        $conn = DatabaseQuery::connect();
    try{
            $query = $conn->prepare('DELETE FROM ' . self::getTableName() . ' WHERE id = '.$value);
            $query->execute();

            $check = $query->rowCount();
            if ($check) {
                return $check;
            } else {
                throw new UserNotFoundException;
            }
        } catch (UserNotFoundException $e) {
        echo $e->errorMessage();
    }
    }

}