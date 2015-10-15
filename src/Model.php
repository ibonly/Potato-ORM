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
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName());
            $query = $conn->prepare($sqlQuery);
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
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName(), $field, $value);
            $query = $conn->prepare($sqlQuery);
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
        $sqlQuery = DatabaseQuery::selectQuery(self::getTableName(), 'id', $value);
        $query = $conn->prepare($sqlQuery);
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
        $connection = DatabaseQuery::connect();
        try{
                if ( ! isset ($this->id)  && ! is_array($this->data) ) {
                    $insertQuery = DatabaseQuery::insertQuery(self::getTableName());
                    $statement = $connection->prepare($insertQuery);
                    $statement->execute();
                    return true;
                }else{
                    $updateQuery = DatabaseQuery::updateQuery(self::getTableName());
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