<?php

namespace Ibonly\SugarORM;

use PDO;
use Exception;
use PDOException;
use Ibonly\SugarORM\DatabaseQuery;
use Ibonly\SugarORM\UserNotFoundException;
use Ibonly\SugarORM\EmptyDatabaseException;
use Ibonly\SugarORM\SaveUserExistException;

class Model extends DatabaseQuery
{

    /**
     * stripclassName
     *
     * @param  get_class_name
     *
     * @return [string] actual class name as table
     */
    public function stripclassName()
    {
        $className = strtolower(get_called_class());
        $r = explode("\\", $className);
        return $r[2];
    }

    /**
     * getTableName
     *
     * @return [type] [description]
     */
    public function getTableName()
    {
        return DatabaseQuery::checkTableName(self::stripclassName());
    }

    public function getALL()
    {
        $connection = DatabaseQuery::connect();
        try{
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName());
            $query = $connection->prepare($sqlQuery);
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll($connection::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                throw new EmptyDatabaseException();
            }
        }catch(EmptyDatabaseException $e){
            echo $e->errorMessage();
        }
    }

    public function where($field, $value)
    {
        $connection = DatabaseQuery::connect();
        try{
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName(), $field, $value);
            $query = $connection->prepare($sqlQuery);
            $query->execute();
            if ($query->rowCount()) {
                return json_encode($query->fetchAll($connection::FETCH_OBJ), JSON_FORCE_OBJECT);
            } else {
                throw new UserNotFoundException();
            }
        }catch(UserNotFoundException $e){
            echo $e->errorMessage();
        }
    }

    public function find($value)
    {
        $connection = DatabaseQuery::connect();
        try
        {
        $sqlQuery = DatabaseQuery::selectQuery(self::getTableName(), 'id', $value);
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ($query->rowCount()) {
            $found = new static;
            $found->id = $value;
            $found->data = $query->fetchAll($connection::FETCH_ASSOC);
            return $found;
        } else {
                throw new UserNotFoundException();
            }
        }catch(UserNotFoundException $e){
            echo $e->errorMessage();
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
        catch(SaveUserExistException $e)
        {
            echo $e->getMessage();
        }
    }

    public function destroy($value)
    {
        $connection = DatabaseQuery::connect();
        try{
                $query = $connection->prepare('DELETE FROM ' . self::getTableName() . ' WHERE id = '.$value);
                $query->execute();
                $check = $query->rowCount();
                if ($check) {
                    return $check;
                } else {
                    throw new UserNotFoundException;
                }
        }
        catch (UserNotFoundException $e) {
            return $e->errorMessage();
        }
    }

    // public function create($ree, $rr)
    // {
    //     $val = "";
        //array_shift($rr);
        // $r = (array)$this;
        // foreach ($r as $key) {
        //     $i++;
        //     $val .= $key;
        // }
        // $conn = DatabaseQuery::connect();

        /*$creatQuery = "CREATE TABLE IF NOT EXISTS peaple (
        id int(11) NOT NULL AUTO_INCREMENT,
        username varchar(20) NOT NULL,
        email varchar(100) NOT NULL,
        password varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";*/
        // $query = $conn->prepare($creatQuery);
        // $query->execute();
    //     return $rr[1];
    // }

    public function increments($value)
    {
        return $value." int(11) NOT NULL AUTO_INCREMENT";
    }
    public function string($value, $length=NULL)
    {
        if( is_null($length))
        {
            return $value ." varchar (20) NOT NULL";
        }
        else
        {
            return $value ." varchar (".$length.") NOT NULL";
        }
    }
    public function text($value)
    {
        return $value." text NOT NULL";
    }
    public function integer($value)
    {
        return $value." int(11) NOT NULL";
    }

}