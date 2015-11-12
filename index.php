<?php

require 'vendor/autoload.php';

use Ibonly\PotatoORM\User;
// use Ibonly\PotatoORM\Schema;
// use Ibonly\PotatoORM\Player;


$sugar = new User();

var_dump($sugar->where('id', '24'));
var_dump($sugar->getAll());



// $sugar->id = NULL;
// $sugar->username = "ibonly1";
// $sugar->email = "ibonl1y@yahoo.com";
// $sugar->password = "1111111";
// echo $sugar->save().PHP_EOL.PHP_EOL;


print_r($sugar->find(50));


// $sugar = User::find(57);
// $sugar->password = "ddddddd";
// echo $sugar->save();

// $sugar = User::destroy(51);
// die($sugar);

// var_dump($suga->getAll());

// $table = new Schema;
// $table->field('increments', 'id');
// $table->field('strings', 'name', 30);
// $table->field('integer', 'number');
// $table->field('primaryKey', 'id');
// echo $table->createTable('players');