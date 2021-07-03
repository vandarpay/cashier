# Vandar Cashier Package Document
============

This package allows you to access and send your requests to **Vandar APIs** easier.

Just do the following steps to prepare for using.



#### #Installation

First, install the Cacshier package for Vandar, using the composer package manager:

	composer require vandar\vandar-cashier



#### #Publish and Database migrations

Vandar Cashier package registers its own database migration directory, so remember to migrate your database after installing the package.

The Vandar Cashier migrations will add a table to your database to store your authentication in the database. It will make your access to authentication values easier


At first you need to publish the migrations by using `vendor:publish` artisan command and then migrate it:
```bash
php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=migrations

php artisan migrate
```

> If you're using Laravel version 5.5+, VandarCashier package will be auto-discovered by Laravel. 

And if not: register the package in **config/app.php** providers array manually:
```php
'providers' => [
	...
	\Vandar\VandarCashier\VandarCashierServiceProvider::class,
],
```



#### #API Key & User/Pass

Next, you should configure your Vandar **Username(mobile)**, **Password** and **API Key** in your application's `.env` file.

You can retrieve your Vandar API Key from the [Vandar control panel](https://dash.vandar.io/)


```bash
VANDAR_USERNAME=
VANDAR_PASSWORD=
VANDAR_API_KEY=
```



#### #Usage

```php

VandarAuth::token() // get token

VandarAuth::login() // login to Vandar account and get token

VandarAuth::isTokenValid() // check the token validation (expired or no?)

VandarAuth::refreshToken() // refresh the token and get new one

```

#### #Credits

 - Vandar - [vandar.io](https://vandar.io)
