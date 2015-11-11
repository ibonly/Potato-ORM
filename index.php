<?php

require 'vendor/autoload.php';

use Ibonly\PotatoORM\User;
// use Ibonly\PotatoORM\Schema;
// use Ibonly\PotatoORM\Player;


$suga = new User();

// echo $sugar->where('id', '24').PHP_EOL.PHP_EOL;


// $sugar = new User();
// $sugar->id = NULL;
// $sugar->username = "ibonly";
// $sugar->email = "ibonly@yahoo.com";
// $sugar->password = "ibonly";
// echo $sugar->save().PHP_EOL.PHP_EOL;


// $sugar = new User();
// echo print_r($sugar->find(2));


// $sugar = User::find(48);
// $sugar->password = "sscoolio";
// echo $sugar->save();

$sugar = User::destroy(51);
// die($sugar);

echo $suga->getAll().PHP_EOL.PHP_EOL;

// $table = new Schema;
// $table->field('increments', 'id');
// $table->field('strings', 'name', 30);
// $table->field('integer', 'number');
// $table->field('primaryKey', 'id');
// echo $table->createTable('players');