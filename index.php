<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\Users;
use Ibonly\SugarORM\SaveUserExistException;


$sugar = new Users();

// echo $sugar->where('user_id', 1).PHP_EOL.PHP_EOL;
// echo $sugar->getAll().PHP_EOL.PHP_EOL;

// $sugar->user_id = NULL;
// $sugar->username = "Shola";
// $sugar->email = "segun@g.com";
// $sugar->password = 1;
// try{
//     $sugar->save();
// } catch (SaveUserExistException $e) {
//     echo $e->errorMessage();
// }
//print_r($sugar).PHP_EOL.PHP_EOL;

// $array = array();

// foreach ($sugar as $key => $value) {
//     echo $key." ".$value.", ";
// }
//var_dump($array);
//
echo $sugar->where('user_id', 1);


