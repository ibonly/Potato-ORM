<?php
/**
 * SugarORM manages the persistence of database CRUD operations.
 *
 * @package Ibonly\SugarORM\Model
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

use PDO;
use Exception;
use PDOException;
use Ibonly\SugarORM\DatabaseQuery;
use Ibonly\SugarORM\ModelInterface;
use Ibonly\SugarORM\UserNotFoundException;
use Ibonly\SugarORM\EmptyDatabaseException;
use Ibonly\SugarORM\SaveUserExistException;

class Model extends DatabaseQuery implements ModelInterface
{
    protected $pluralize;

    //Inject the inflector trait
    use Inflector;

    public function __construct()
    {
        $this->pluralize = self::pluralize(self::stripclassName());
    }

    /**
     * stripclassName()
     *
     * @return string
     */
    public function stripclassName()
    {
        $className = strtolower(get_called_class());
        $r = explode("\\", $className);
        return $r[2];
    }

    /**
     * getClassName()
     *
     * @return [string] Plural form of class name
     */
    public function getClassName()
    {
        return $this->pluralize;
    }

    /**
     * getTableName()
     *
     * @return [string] actual tablename from database
     */
    public function getTableName()
    {
        return DatabaseQuery::checkTableName(self::getClassName());
    }

    /**
     * getALL()
     * Get all record from the database
     *
     * @return [json]
     */
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
        } catch (EmptyDatabaseException $e){
            echo $e->errorMessage();
        }
    }

    /**
     * where($field, $value)
     * Get data from database where $field = $value
     *
     * @return array
     */
    public function where($field, $value)
    {
        $connection = DatabaseQuery::connect();
        try{
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName(), $field, $value);
            $query = $connection->prepare($sqlQuery);
            $query->execute();
            if ($query->rowCount()) {
                return $query->fetchAll($connection::FETCH_OBJ);
            } else {
                throw new UserNotFoundException();
            }
        } catch (UserNotFoundException $e){
            echo $e->errorMessage();
        }
    }

    /**
     * find($value)
     * Find data from database where id = $value
     *
     * @return array
     */
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
        } catch (UserNotFoundException $e){
            echo $e->errorMessage();
        }
    }

    /**
     * save()
     * Insert data into database
     *
     * @return bool
     */
    public function save()
    {
        $connection = DatabaseQuery::connect();
        try{
                if ( ! isset ($this->id)  && ! isset($this->data) ) {
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
        } catch (PDOException $e){
            throw new  SaveUserExistException($e->getMessage());
        }
        catch(SaveUserExistException $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * destroy($value)
     * Delete data from database
     *
     * @return bool
     */
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
        } catch (UserNotFoundException $e) {
            return $e->errorMessage();
        }
    }

}