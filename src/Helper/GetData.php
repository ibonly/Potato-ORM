<?php

namespace Ibonly\PotatoORM;

use Ibonly\PotatoORM\GetDataInterface;

class GetData implements GetDataInterface
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * get all the data in the table as an array
     */
    protected function getAllData()
    {
        return $this->value;
    }

    /**
     * Reverse the json data
     * @param [int] $limit
     */
    protected function DESC($limit = NULL)
    {
        $value = array_reverse($this->value);
        if ($limit === NULL) {
            return $value;
        } else {
            return array_slice($value, 0, $limit);
        }
    }

    /**
     * Return data in descending order
     * @param  [int] $limit
     */
    public function allDESC($limit = NULL)
    {
        return json_decode(json_encode($this->DESC($limit)));
    }

    /**
     * Get all data in json_decoded format
     */
    public function all()
    {
        return json_decode(json_encode($this->getAllData()));
    }

    /**
     * Get the actual fetched row and return an array
     */
    public function toArray()
    {
        return current($this->value);
    }

    /**
     * Convert the fetched row to json
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert the fetched row to json
     */
    public function toJsonDecode()
    {
        return json_decode($this->toJson());
    }

    /**
     * Get the output of the first jsondecoded element
     */
    public function first()
    {
        return json_decode($this->toJson());
    }

    /**
     * Get the count of the fetch element
     */
   public function getCount()
   {
        return count($this->value);
   }
}