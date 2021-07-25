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



#### # Enviroment Variables

For proper package operation, you must define some enviroment variables in `.env` file to be used during the processes:


```bash
VANDAR_USERNAME=
VANDAR_PASSWORD=
VANDAR_BUSINESS_NAME=
VANDAR_API_KEY=
VANDAR_CALLBACK_URL=
VANDAR_NOTIFY_URL=
```
**-> tips**:
-  You can retrieve your Vandar API Key from the [Vandar.Dashboard](https://dash.vandar.io/)
-  Your _Username_ is your mobile number With which you registered in Vandar
-  CALLBACK_URL must be one of the URLs that you added in your Vandar.Dashboard



#### #Model
VandarPayment Model and table, use the Polyphorphism relations

**payable**(person who pay)
    
**paymentable**(what the payment was done for)

So if you want, use one of these traits(As needed) in your Model:

```php
use Vandar\VandarCashier\Traits\Payable
use Payable;

use Vandar\VandarCashier\Traits\Paymentable
use Paymentable;

```



#### #**USAGE**
For Using all the package method, you just need to use main Vandar file namespace in your files that you use it:
```php
use Vandar\VandarCashier\Vandar;
```

#### #Authentication
```php
Vandar::Auth()->token(); // Get token

Vandar::Auth()->login(); // Login to Vandar account and get token

Vandar::Auth()->isTokenValid(); // Check the token validation (expired or no?)

Vandar::Auth()->refreshToken(); // Refresh the token and get new one

```



#### #IPG
```php
Vandar::IPG()->pay($params); // pass **$payment** parameter that mentioned in the Vandar Document to do the all payment process.
```
+ **NOTE!**: To Verify the payment after return from payment page, you must just add

```php
Vandar::Verify();
```
in your the {callback page} that you added its URL(callback_url) in your Vandar Account to verify and continue the transaction process. 




#### #Settlement
```php
Vandar::Settlement()->list(); // Get the list of settlements

Vandar::Settlement()->store(); // Store new settlement

Vandar::Settlement()->show(); // Get more details about a specific settlement

Vandar::Settlement()->cancel(); // Cancel the specific settlement
```



#### #Billing
```php
Vandar::Bills()->balance(); // Get the current balance of your business

Vandar::Bills()->list(); // Get the list of billing of your business
```



#### #Business
```php
Vandar::Business()->list(); // Get the list of your businesses of your Vandar account

Vandar::Business()->info(); // Show the informations about the specific business

Vandar::Business()->users(); // Get the list of busniess users with their informations
```



#### #Mandate
```php
Vandar::Mandate()->list(); // Get the list of confirmed Mandates

Vandar::Mandate()->store(); // Store new Mandate

Vandar::Mandate()->show(); // Show the informations of specific mandate

Vandar::Mandate()->revoke(); // Revoke specific mandate
```
+ **NOTE!**: To Verify the payment after return from payment page, you must just add

```php
Vandar::Verify();
```
in your the {callback page} that you added its URL(callback_url) in your Vandar Account to verify and continue the transaction process. 


#### #Withdrawal
```php
Vandar::Withdrawal()->list(); // Get the list of Withdrawals

Vandar::Withdrawal()->store(); // Store new withdrawal

Vandar::Withdrawal()->show(); // Show details about specific withdrawal

Vandar::Withdrawal()->cancel(); // Cancel the specific withdrawal
```



#### #Credits

 - Vandar - [vandar.io](https://vandar.io)
