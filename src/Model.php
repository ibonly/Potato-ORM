<?php

namespace Ibonly\SugarORM;

use Ibonly\SugarORM\DBConfig;

class Model extends DBConfig
{
    public function getClass()
    {
        return get_called_class();
    }
}