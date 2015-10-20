<?php
/**
 * Defining Interface for class Model.
 *
 * @package Ibonly\SugarORM\ModelInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

interface ModelInterface
{

    public function getclassName();

    public function stripclassName();

    public function getTableName();

    public function getALL();

    public function where($field, $value);

    public function find($value);

    public function save();

    public function destroy($value);

}