<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\Users;
use Ibonly\SugarORM\SaveUserExistException;
use Ibonly\SugarORM\UserNotFoundException;


$sugar = new Users();
// echo $sugar->where('id', 2).PHP_EOL.PHP_EOL;
try{
    echo $sugar->getAll().PHP_EOL.PHP_EOL;
} catch (SaveUserExistException $e) {
echo $e->errorMessage();
}

// $sugar = new Users();
// $sugar->id = NULL;
// $sugar->username = "alayande";
// $sugar->email = "ikechukwu@dede.com";
// $sugar->password = "password123";
// try{
//     echo $sugar->save();
// } catch (SaveUserExistException $e) {
//     echo $e->errorMessage();
// }


// $sugar = new Users();
// print_r($sugar->find(2));


// $sugar = Users::find(19);
// $sugar->password = "password";
// echo $sugar->save();

    // echo $sugar = Users::destroy(2);
    // die($sugar);

// $n = new Users();
// print_r($n->selectQuery());