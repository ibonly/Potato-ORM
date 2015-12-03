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

    public function toArray()
    {
        return $this->value;
    }

    public function toJson()
    {
        return json_encode($this->value);
    }

    public function toJsonDecode()
    {
        return json_decode( json_encode($this->value) );
    }

    public function getData($name)
    {
        foreach (json_decode($this->all()) as $key) {
            return $key->$name;
        }
   }

   public function getCount()
   {
        return sizeof($this->value);
   }
}