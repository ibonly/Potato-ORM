<?php
/**
 * PotatoORM manages the persistence of database CRUD operations.
 *
 * @package Ibonly\PotatoORM\Model
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

use PDO;
use Exception;
use PDOException;
use Ibonly\PotatoORM\DatabaseQuery;
use Ibonly\PotatoORM\ModelInterface;
use Ibonly\PotatoORM\UserNotFoundException;
use Ibonly\PotatoORM\EmptyDatabaseException;
use Ibonly\PotatoORM\SaveUserExistException;
use Ibonly\PotatoORM\ColumnNotExistExeption;
use Ibonly\PotatoORM\InvalidConnectionException;

class Model extends DatabaseQuery implements ModelInterface
{
    //Inject the inflector trait
    use Inflector;

    /**
     * stripclassName()
     *
     * @return string
     */
    public function stripclassName()
    {
        $className = strtolower(get_called_class());
        $r = explode("\\", $className);
        return end($r);
    }

    /**
     * getClassName()
     *
     * @return string
     */
    public function getClassName()
    {
        return self::pluralize(self::stripclassName());
    }

    /**
     * getTableName()
     *
     * @return string
     */
    public function getTableName($connection = NULL)
    {
        return DatabaseQuery::checkTableName(self::getClassName(), $connection);
    }

    /**
     * getALL()
     * Get all record from the database
     *
     * @return object
     */
    public function getALL($dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);
        try
        {
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName($connection));
            $query = $connection->prepare($sqlQuery);
            $query->execute();
            if ( $query->rowCount() )
            {
                return $query->fetchAll($connection::FETCH_ASSOC);
            }
            throw new EmptyDatabaseException();
        } catch ( EmptyDatabaseException $e ){
            echo $e->errorMessage();
        } catch ( TableDoesNotExistException $e ){
            echo $e->errorMessage();
        } catch ( InvalidConnectionException $e ){
            echo $e->errorMessage();
        }
    }

    /**
     * where($field, $value)
     * Get data from database where $field = $value
     *
     * @return object
     */
    public function where($field, $value, $dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);
        try
        {
            $sqlQuery = DatabaseQuery::selectQuery(self::getTableName($connection), $field, $value, $connection);
            $query = $connection->prepare($sqlQuery);
            $query->execute();
            if ( $query->rowCount() )
            {
                return json_encode($query->fetchAll($connection::FETCH_OBJ), JSON_FORCE_OBJECT);
            }
            throw new UserNotFoundException();
        } catch ( UserNotFoundException $e ){
            echo $e->errorMessage();
        } catch ( TableDoesNotExistException $e ){
            echo $e->errorMessage();
        }  catch ( InvalidConnectionException $e ){
            echo $e->errorMessage();
        }  catch ( ColumnNotExistExeption $e ){
            echo $e->errorMessage();
        }
    }

    /**
     * find($value)
     * Find data from database where id = $value
     *
     * @return array
     */
    public function find($value, $dbConnection = NULL)
    {
        return self::where('id', $value, $dbConnection);
    }

    /**
     * save()
     * Insert data into database
     *
     * @return bool
     */
    public function save($dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);
        try
        {
            if ( ! isset ($this->id)  && ! isset($this->data) )
            {
                $query = DatabaseQuery::insertQuery(self::getTableName());
                $statement = $connection->prepare($query);
                if( $statement->execute() )
                {
                    return true;
                }
                throw new  SaveUserExistException();
            }
            $updateQuery = DatabaseQuery::updateQuery(self::getTableName());
            $statement = $connection->prepare($updateQuery);
            if( $statement->execute() )
            {
                return true;
            }
            throw new  SaveUserExistException();
        } catch ( PDOException $e ){
            throw new  SaveUserExistException($e->getMessage());
        } catch( SaveUserExistException $e ) {
            return $e->getMessage();
        } catch ( TableDoesNotExistException $e ){
            echo $e->errorMessage();
        }  catch ( InvalidConnectionException $e ){
            echo $e->errorMessage();
        }  catch ( ColumnNotExistExeption $e ){
            echo $e->errorMessage();
        }
    }

    /**
     * destroy($value)
     * Delete data from database
     *
     * @return bool
     */
    public function destroy($value, $dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);
        try
        {
            $query = $connection->prepare('DELETE FROM ' . self::getTableName() . ' WHERE id = '.$value);
            $query->execute();
            $check = $query->rowCount();
            if ($check)
            {
                return true;
            }
            throw new UserNotFoundException;
        } catch ( UserNotFoundException $e ) {
            return $e->errorMessage();
        } catch ( TableDoesNotExistException $e ){
            echo $e->errorMessage();
        }  catch ( InvalidConnectionException $e ){
            echo $e->errorMessage();
        }  catch ( ColumnNotExistExeption $e ){
            echo $e->errorMessage();
        }
    }

}