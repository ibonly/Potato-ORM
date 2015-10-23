<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\User;
use Ibonly\SugarORM\Child;
use Ibonly\SugarORM\Schema;
use Ibonly\SugarORM\Inflector;
use Ibonly\SugarORM\UserNotFoundException;
use Ibonly\SugarORM\SaveUserExistException;
use Ibonly\SugarORM\DBConfig;

// echo Inflector::pluralize("knife");


$sugar = new User();
// $db = new DBConfig;
// echo $sugar->getTableName();
// echo $sugar->checkTableExist('uses', $db);
// echo $sugar->where('id', 1).PHP_EOL.PHP_EOL;
echo $sugar->getAll().PHP_EOL.PHP_EOL;


// $sugar = new User();
// $sugar->id = NULL;
// $sugar->username = "alanxzde";
// $sugar->email = "ikechu@zxzdede.com";
// $sugar->password = "passwxzxxord123";
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
$table->field('strings', 'milk', 30);
$table->field('strings', 'name');
$table->field('text', 'body');
$table->field('text', 'cool');
$table->field('strings', 'email', 100);
$table->field('primaryKey', 'id');
$table->field('foreignKey', 'id', 'users_id');

echo $table->createTable('peopless');