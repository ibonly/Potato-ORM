<?php

require 'vendor/autoload.php';

use Ibonly\PotatoORM\User;
use Ibonly\PotatoORM\Schema;
// use Ibonly\PotatoORM\Player;


// $sugar = new User();

$result = User::where('id', '1000');
print_r($result);


// $result = $sugar->getAll();
// print_r($result);



// $sugar->id = NULL;
// $sugar->username = "ibraheem";
// $sugar->email = "ibon@yahoo.com";
// $sugar->password = "1111111";
// echo $sugar->save().PHP_EOL.PHP_EOL;


// print_r($sugar->find(50));


// $sugar = User::find(100);
// $sugar->password = "password";
// echo $sugar->save();

// $sugar = User::destroy(63);
// die($sugar);

// var_dump($suga->getAll());

// $table = new Schema;
// $table->field('increments', 'id');
// $table->field('strings', 'name', 30);
// $table->field('integer', 'number');
// $table->field('primaryKey', 'id');
// echo $table->createTable('players');