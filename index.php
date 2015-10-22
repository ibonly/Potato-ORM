<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\User;
use Ibonly\SugarORM\Child;
use Ibonly\SugarORM\Schema;
use Ibonly\SugarORM\Inflector;
use Ibonly\SugarORM\UserNotFoundException;
use Ibonly\SugarORM\SaveUserExistException;

// echo Inflector::pluralize("knife");


// $sugar = new User();
// echo $sugar->getTableName();

// echo $sugar->where('id', 1).PHP_EOL.PHP_EOL;
// echo $sugar->getAll().PHP_EOL.PHP_EOL;


// $sugar = new Users();
// $sugar->id = NULL;
// $sugar->username = "alande";
// $sugar->email = "ikechu@dede.com";
// $sugar->password = "password123";
// try{
//     echo $sugar->save();
// } catch (SaveUserExistException $e) {
//     echo $e->errorMessage();
// }


// $sugar = new Users();
// print_r($sugar->find(2));


// $sugar = Users::find(2);
// $sugar->password = "password";
// echo $sugar->save();

    // $sugar = Users::destroy(2);
    // die($sugar);
    //

$table = new Schema;
$table->field('increments', 'id');
$table->field('string', 'milk', 30);
$table->field('string', 'name');
$table->field('text', 'body');
$table->field('text', 'cool');
$table->field('string', 'email', 100);
$table->field('primaryKey', 'id');
$table->field('foriegnKey', 'id', 'useers_id');

echo $table->createTable('peoples');a