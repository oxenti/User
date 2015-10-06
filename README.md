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
