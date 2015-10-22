<?php
/**
 * Defining Interface for class SchemaInterface.
 *
 * @package Ibonly\SugarORM\DatabaseQueryInterface
 * @author  Ibraheem ADENIYI <ibonly01@gmail.com>
 * @license MIT <https://opensource.org/licenses/MIT>
 */

namespace Ibonly\SugarORM;

interface SchemaInterface
{
    public function field($type, $fieldName, $length);

    public function buildQuery($tablename);

    public function sanitizeQuery($query);

    public function createTable($tablename);

    public function increments($value);

    public function strings($value, $length);

    public function text($value);

    public function integer($value, $length);

    public function foreignKey($value, $length);

}