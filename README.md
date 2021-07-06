# Vandar Cashier Package Document
============

This package allows you to access and send your requests to **Vandar APIs** easier.

Just do the following steps to prepare for using.



#### #Installation

First, install the Cacshier package for Vandar, using the composer package manager:

	composer require vandar\vandar-cashier



#### #Publish and Database migrations

Vandar Cashier package registers its own database migration directory, so remember to migrate your database after installing the package.

The Vandar Cashier migrations will add 2 tables into your database to save:
1. Your authentication data (for easy access to authentication data)
2. All transaction data that you get from Vandar response (For a detailed review of all transactions)



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



#### #Use Package
To use Vandar package in your project, you must **use** it everywhere you want work with it:

```php
use Vandar\VandarCashier;

```

#### #Model
VandarPayment Model and table, use the Polyphorphism relations

**payable**(person who pay)
    
**paymentable**(what the payment was done for)

So if you want, use one of these traits(As needed) in your Model:

```php
use Payable;

use Paymentable;

```

#### #**Usage**

#### #Authentication
```php
VandarAuth::token() // get token

VandarAuth::login() // login to Vandar account and get token

VandarAuth::isTokenValid() // check the token validation (expired or no?)

VandarAuth::refreshToken() // refresh the token and get new one

```

#### #Authentication
```php
VandarIPG::pay($params) // pass **$payment** parameter that mentioned in the Vandar Document to do the all payment process.

VandarIPG::verifyPayment()	// to Verify the payment after return from payment page, you must use this method in the {callback page} that you added its URL(callback_url) in your Vandar Account to verify and continue the transaction process 

```


#### #Credits

 - Vandar - [vandar.io](https://vandar.io)
