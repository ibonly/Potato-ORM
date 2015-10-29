# Potato-ORM

[![Build Status](https://travis-ci.org/andela-iadeniyi/Potato-ORM.svg)](https://travis-ci.org/andela-iadeniyi/Potato-ORM)
[![License](http://img.shields.io/:license-mit-blue.svg)](https://github.com/andela-iadeniyi/Potato-ORM/blob/master/LICENCE)
[![Quality Score](https://img.shields.io/scrutinizer/g/andela-iadeniyi/Potato-ORM.svg?style=flat-square)](https://scrutinizer-ci.com/g/andela-iadeniyi/Potato-ORM)
[![Scruitinizer Code](https://scrutinizer-ci.com/g/andela-iadeniyi/Potato-ORM/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andela-iadeniyi/Potato-ORM)

Potato-ORM is a package that manages the CRUD operation of database

## Installation

[PHP](https://php.net) 5.5+ and [Composer](https://getcomposer.org) are required.

Via Composer

```
$ composer require ibonly/potato-orm
```

```
$ composer install
```

## Usage

### App Namespace

```
    namespace Ibonly\PotatoORM

```

Create a `Class` that correspond to the singular form of the table name in the database. i.e.

```
<?php
    namespace Ibonly\PotatoORM;

    class User extends Model
    {

    }
?>
```
The `Model` class contains `getAll()`, `where($field, $value)`, `find($value)`, `save()` and `detroy($id)` methods.

### getAll()

```
<?php
    use Ibonly\PotatoORM\User;

    $sugar = new User();
    echo $sugar->getAll();
?>
```

    `Return type = JSON`

### where($field, $value)

```
<?php
    use Ibonly\PotatoORM\User;

    $sugar = new User();
    echo $sugar->where($field, $value);
?>
```

    `Return type = JSON`

### find($value)

```
<?php
    use Ibonly\PotatoORM\User;

    $insert = User::find(1);
    $insert->password = "password";
    echo $insert->save()
?>
```

    To return custom message, wrap the `save()` method in an `if statement`

    `Return type = Boolean`

### save()

```
<?php
    use Ibonly\PotatoORM\User;
    use Ibonly\PotatoORM\SaveUserExistException;

     $insert = new User();
     $insert->id = NULL;
     $insert->username = "alanxzde";
     $insert->email = "ikechu@zxzdede.com";
     $insert->password = "passwxzxxord123";
     try{
         echo $insert->save();
     } catch (SaveUserExistException $e) {
         echo $e->errorMessage();
    }
?>
```

    To return custom message, wrap the `save()` method in an `if statement`

    `Return type = Boolean`

### detroy($value)

```
<?php
    use Ibonly\PotatoORM\User;

    $insert = User::destroy(2);
    die($insert);
?>
```

    `Return type = Boolean`

## Create Database Table

Its is also possible to create Database Table with the `Schema` class. The table name will be specified in the
`createTable($name)` method.

```
<?php
    use Ibonly\PotatoORM\Schema;

    $user = new Schema;
    $user->field('increments', 'id');
    $user->field('strings', 'username');
    $user->field('strings', 'name', 50);
    $user->field('integer', 'age');
    $user->field('primaryKey', 'id');

    echo $table->createTable('players');
?>
```
    `Return type = Boolean`

#### Database Constraint

    Foreign Key

    ```
        $user->field('foreignKey', 'id', 'users_id');
    ```


    The reference table `(users)` and field `(id)` will be written as `(users_id)`


    Unique

    ```
        $user->field('unique', 'email')
    ```


## Testing

```
$ vendor/bin/phpunit test
```

## Contributing

To contribute and extend the scope of this package,
Please check out [CONTRIBUTING](CONTRIBUTING.md) file for detailed contribution guidelines.

## Credits

Open-source-Evangelist is created and maintained by `Ibraheem ADENIYI`.