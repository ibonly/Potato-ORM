<?php

require 'vendor/autoload.php';

use Ibonly\SugarORM\Users;
use Ibonly\SugarORM\SaveUserExistException;
use Ibonly\SugarORM\UserNotFoundException;


// $sugar = new Users();
// echo $sugar->where('id', 1).PHP_EOL.PHP_EOL;
// echo $sugar->getAll().PHP_EOL.PHP_EOL;

// $sugar = new Users();
// $sugar->id = NULL;
// $sugar->username = "Sasdh2owasdla21";
// $sugar->email = "segusada22adnw1@g.com";
// $sugar->password = 12111111;
// try{
//     echo $sugar->save();
// } catch (SaveUserExistException $e) {
//     echo $e->errorMessage();
// }


// $sugar = new Users();
// print_r($sugar->find(2));


// $sugar = Users::find(1);
// $sugar->password = "adasdsad";
// $sugar->email = "ade@ade.com";
// echo $sugar->save();

    echo $sugar = Users::destroy(47);
    die($sugar);

