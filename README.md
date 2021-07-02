# Vandar Cashier Package Document
============

This package allows you to access and send your requests to **Vandar APIs** easier.

Just do the following steps to prepare for using.


#### Composer Install (for Laravel 5+)

	composer require vandar\vandar-cashier

#### Publish and Run the migrations


```bash
php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=migrations

php artisan migrate
```


If you're using Laravel version 5.5+, VandarCashier package will be auto-discovered by Laravel. 

And if not: register the package in **config/app.php** providers array manually:
```php
'providers' => [
	...
	\Vandar\VandarCashier\VandarCashierServiceProvider::class,
],
```


Add your **Username(mobile)**, **Password** of your [Vandar Account](https://vandar.io/) and **API_KEY** with these enviroment variables in the enviroment file **(.env)**

```bash
VANDAR_USERNAME=
VANDAR_PASSWORD=
VANDAR_API_KEY=
```


#### Usage

```php

VandarAuth::token() // get token

VandarAuth::login() // login to Vandar account and get token

VandarAuth::isTokenValid() // check the token validation (expired or no?)

VandarAuth::refreshToken() // refresh the token and get new one

```

#### Credits

 - Vandar - [vandar.io](https://vandar.io)
