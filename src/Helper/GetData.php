<?php

namespace Ibonly\PotatoORM;

use Ibonly\PotatoORM\GetDataInterface;

class GetData implements GetDataInterface
{
    protected $value;

    function __construct($value)
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

    public function all()
    {
        return json_decode( json_encode( $this->getAllData() ) );
    }

    /**
     * Get the actual fetched row and return an array
     */
    protected function toArray()
    {
        return current($this->value);
    }

    /**
     * Convert the fetched row to json
     */
    protected function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Get the output of the first jsondecoded element
     */
    public function first()
    {
        return json_decode( $this->toJson() );
    }

    /**
     * Get the count of the fetch element
     */
   public function getCount()
   {
        return sizeof($this->value);
   }
}