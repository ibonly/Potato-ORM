<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\Users;
use Ibonly\SugarORM\SaveUserExistException;
use Ibonly\SugarORM\UserNotFoundException;
// use Exception;

// $sugar = new Users();
// try
// echo $sugar->where('id', 2).PHP_EOL.PHP_EOL;
   // echo $sugar->getAll().PHP_EOL.PHP_EOL;
// }catch(Exception $e){
//     echo $e->getMessage();
// }

$sugar = new Users();
$sugar->id = NULL;
$sugar->username = "alayande";
$sugar->email = "ikechukwu@dede.com";
$sugar->password = "password123";
try{
    echo $sugar->save();
} catch (SaveUserExistException $e) {
    echo $e->errorMessage();
}


// $sugar = new Users();
// print_r($sugar->find(2));


// echo $sugar = Users::find(19);
// $sugar->password = "password";
// echo $sugar->save();

    // echo $sugar = Users::destroy(2);
    // die($sugar);
    //

// $ee = Users::create('channels', function($table){
// $table->increments('id');
// $table->string('channel_name', 30);
// $table->text('channel_description');
// $table->integer('subscription_count');
// });
// var_dump($ee);