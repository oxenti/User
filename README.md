# OxenTI User API plugin for CakePHP 3

This plugin contains a package with API methods for managing Users on a CakePHP 3 application. This plugin implements Authentication and Authorization tasks.

## Requirements

* CakePHP 3.0+

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```sh
composer require oxenti/user
```

## Usage

In your app's `config/bootstrap.php` add:

```php
// In config/bootstrap.php
Plugin::load('User');
```

or using cake's console:

```sh
./bin/cake plugin load User
```

## Configuration

In your app's 'config/app.php' add this to your Datasources array:

```php
	'oxenti_user' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'host' => 'ỳour_db_host',
        'username' => 'username',
        'password' => 'password',
        'database' => 'databse_name',
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
        'log' => false,
        'quoteIdentifiers' => false,
    ],
```