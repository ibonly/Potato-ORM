<?php
/**
 * Defining Interface for class Model.
 *
 * @package Ibonly\PotatoORM\ModelInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\PotatoORM;

interface ModelInterface
{
    public function getALL();

    public function where($field, $value);

    public static function find($value);

    public function save();

    public function update($id);

    public function destroy($value);

    public function query($query);
}