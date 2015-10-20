<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\User;
use Ibonly\SugarORM\Child;
use Ibonly\SugarORM\Inflector;
use Ibonly\SugarORM\UserNotFoundException;
use Ibonly\SugarORM\SaveUserExistException;

// echo Inflector::pluralize("knife");


$sugar = new User();
// echo $sugar->getTableName();

echo $sugar->where('id', 1).PHP_EOL.PHP_EOL;
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

// $ee = Users::create('channels', function($table){
// $table->increments('id');
// $table->string('channel_name', 30);
// $table->text('channel_description');
// $table->integer('subscription_count');
// });
// var_dump($ee);