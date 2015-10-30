<?php

require 'vendor/autoload.php';

use Ibonly\PotatoORM\User;
use Ibonly\PotatoORM\Schema;
use Ibonly\PotatoORM\Player;
use Ibonly\PotatoORM\UserNotFoundException;
use Ibonly\PotatoORM\SaveUserExistException;
use Ibonly\PotatoORM\ColumnNotExistExeption;


// $sugar = new User();
// echo $sugar->checkColumn('players', 'iddfdd');

// echo $sugar->where('id', 23).PHP_EOL.PHP_EOL;
// echo $sugar->getAll().PHP_EOL.PHP_EOL;


// $sugar = new Player();
// $sugar->id = NULL;
// $sugar->name = "tunde";
// $sugar->number = 3;
// try{
//     echo $sugar->save();
// } catch (SaveUserExistException $e) {
//     echo $e->errorMessage();
// }


// $sugar = new Player();
// echo print_r($sugar->find(2));


$sugar = Player::find(1);
$sugar->name = "wordeded";
echo $sugar->save();

// $sugar = Player::destroy(2);
// die($sugar);


// $table = new Schema;
// $table->field('increments', 'id');
// $table->field('strings', 'name', 30);
// $table->field('integer', 'number');
// $table->field('primaryKey', 'id');
// echo $table->createTable('players');