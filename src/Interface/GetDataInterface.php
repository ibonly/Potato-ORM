<?php
/**
 * Defining Interface for class GetData.
 *
 * @package Ibonly\PotatoORM\ModelInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

interface GetDataInterface
{
    public function allDESC($limit);

    public function all();

    public function toArray();

    public function all();

    public function first();

    public function getCount();
}
