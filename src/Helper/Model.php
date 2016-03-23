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
use Ibonly\PotatoORM\GetData;
use Ibonly\PotatoORM\DatabaseQuery;
use Ibonly\PotatoORM\ModelInterface;
use Ibonly\PotatoORM\DataNotFoundException;
use Ibonly\PotatoORM\EmptyDatabaseException;
use Ibonly\PotatoORM\ColumnNotExistExeption;
use Ibonly\PotatoORM\DataAlreadyExistException;
use Ibonly\PotatoORM\InvalidConnectionException;

class Model extends DatabaseQuery implements ModelInterface
{
    //Inject the inflector trait
    use Inflector, Upload;

    protected $ouput;

    /**
     * stripclassName()
     *
     * @return string
     */
    public static function stripclassName()
    {
        $className = strtolower(get_called_class());
        $nameOfClass = explode("\\", $className);

        return end($nameOfClass);
    }

    /**
     * Get the table name if defined in the model
     * 
     * @return string
     */
    public function tableName()
    {
        if(isset($this->table)) {
            $this->output = $this->table;
        } else {
            $this->output = null;
        }

        return $this->output;
    }

    /**
     * Get the fields to be fillables defined in the model
     * 
     * @return string
     */
    public function fields()
    {
        if (isset($this->fillables)) {
            if (sizeof($this->fillables) > 0) {
                $this->output = implode(", ", $this->fillables);
            } else {
                $this->output = '*';
            }
        } else {
            $this->output = '*';
        }

        return $this->output;
    }

    /**
     * getClassName()
     *
     * @return string
     */
    public function getClassName()
    {
        if ($this->tableName() === null) {
            $this->output = self::pluralize(self::stripclassName());
        } else {
            $this->output = $this->tableName();
        }

        return $this->output;
    }

    /**
     * getTableName()
     *
     * @return string
     */
    public function getTableName($connection)
    {
        return DatabaseQuery::checkTableName($this->getClassName(), $connection);
    }

    /**
     * getALL()
     * Get all record from the database
     *
     * @return object
     */
    public function getAll($dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);

        $sqlQuery = DatabaseQuery::selectAllQuery(self::getTableName($connection), self::fields());
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ( $query->rowCount() )
        {
            return new GetData($query->fetchAll($connection::FETCH_ASSOC));
        }
        throw new EmptyDatabaseException();
    }

    /**
     * where($data, $condition)
     * Get data from database where $data = $condition
     *
     * @return object
     */
    public function where($data, $condition = NULL, $dbConnection = NULL)
    {
        $databaseQuery = new DatabaseQuery();
        $connection = $databaseQuery->checkConnection($dbConnection);

        $sqlQuery = $databaseQuery->selectQuery(self::getTableName($connection), self::fields(), $data, $condition, $connection);
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ( $query->rowCount() )
        {
            return new GetData($query->fetchAll($connection::FETCH_ASSOC));
        }
        throw new DataNotFoundException();
    }

    /**
     * find($value)
     * Find data from database where id = $value
     *
     * @return array
     */
    public static function find($value, $dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);

        $sqlQuery = DatabaseQuery::selectQuery(self::getTableName($connection), self::fields(), ['id' => $value], NULL, $connection);
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ( $query->rowCount() )
        {
            $found = new static;
            $found->id = $value;
            $found->data = $query->fetchAll($connection::FETCH_ASSOC);
            return $found;
        }
        throw new DataNotFoundException();
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

        $query = $this->insertQuery(self::getTableName($connection));
        $statement = $connection->prepare($query);
        if( $statement->execute() )
        {
            return true;
        }
        throw new  DataAlreadyExistException();

    }

    /**
     * update()
     * Update details in database after ::find(2)
     *
     * @return bool
     */
    public function update($dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);

        $updateQuery = $this->updateQuery(self::getTableName($connection));
        $statement = $connection->prepare($updateQuery);
        if( $statement->execute() )
        {
            return true;
        }
        throw new  DataAlreadyExistException();
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

        $query = $connection->prepare('DELETE FROM ' . self::getTableName($connection) . ' WHERE id = '.$value);
        $query->execute();
        $check = $query->rowCount();
        if ($check)
        {
            return true;
        }
        throw new DataNotFoundException;
    }
}