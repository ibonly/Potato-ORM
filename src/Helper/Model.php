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
use Ibonly\PotatoORM\DataNotFoundException;
use Ibonly\PotatoORM\EmptyDatabaseException;
use Ibonly\PotatoORM\ColumnNotExistExeption;
use Ibonly\PotatoORM\DataAlreadyExistException;
use Ibonly\PotatoORM\InvalidConnectionException;

class Model extends Relationships implements ModelInterface, RelationshipsInterface
{
    /**
     * getALL()
     * Get all record from the database
     *
     * @return object
     */
    public function getAll($dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);

        $sqlQuery = self::whereClause();
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ($query->rowCount()) {
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

        $sqlQuery = self::whereClause($data, $condition, $connection);
        $query = $connection->prepare($sqlQuery);
        $query->execute();
        if ($query->rowCount()) {
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
        if ($query->rowCount()) {
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
        if($statement->execute()) {
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
    public function update($id, $dbConnection = NULL)
    {
        $connection = DatabaseQuery::checkConnection($dbConnection);

        $updateQuery = $this->updateQuery(self::getTableName($connection), $id);
        $statement = $connection->prepare($updateQuery);
        if ($statement->execute()) {
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
        if ($check) {
            return true;
        }
        throw new DataNotFoundException;
    }
}