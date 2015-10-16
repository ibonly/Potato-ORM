<?php
/**
 * Defining Interface for class DBConfigInterface.
 *
 * @package Ibonly\SugarORM\DBConfigInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

interface DBConfigInterface
{

    public function connect();

    public function loadEnv();

}