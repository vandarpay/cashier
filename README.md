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


#### #Authentication
```php
use Vandar\VandarCashier\VandarAuth;


VandarAuth::token(); // Get token

VandarAuth::login(); // Login to Vandar account and get token

VandarAuth::isTokenValid(); // Check the token validation (expired or no?)

VandarAuth::refreshToken(); // Refresh the token and get new one

```



#### #IPG
```php
use Vandar\VandarCashier\VandarIPG;


VandarIPG::pay($params); // pass **$payment** parameter that mentioned in the Vandar Document to do the all payment process.
```
+ **NOTE!**: To Verify the payment after return from payment page, you must:

1. use `VandarVerify` trait

2. call `verify method` as static

```php
use Vandar\VandarCashier\Traits\VandarVerify;

use VandarVerify;

self::VandarVerify(); // Call verify method
```
in your the {callback page} that you added its URL(callback_url) in your Vandar Account to verify and continue the transaction process. 




#### #Settlement
```php
use Vandar\VandarCashier\VandarSettlement;


VandarSettlement::list(); // Get the list of settlements

VandarSettlement::store(); // Store new settlement

VandarSettlement::show(); // Get more details about a specific settlement

VandarSettlement::cancel(); // Cancel the specific settlement
```



#### #Billing
```php
use Vandar\VandarCashier\VandarBills;


VandarBills::balance(); // Get the current balance of your business

VandarBills::list(); // Get the list of billing of your business
```



#### #Business
```php
use Vandar\VandarCashier\VandarBusiness;


VandarBusiness::list(); // Get the list of your businesses of your Vandar account

VandarBusiness::info(); // Show the informations about the specific business

VandarBusiness::users(); // Get the list of busniess users with their informations

```



#### #Mandate
```php
use Vandar\VandarCashier\VandarMandate;


VandarMandate::list(); // Get the list of confirmed Mandates

VandarMandate::store(); // Store new Mandate

VandarMandate::show(); // Show the informations of specific mandate

VandarMandate::revoke(); // Revoke specific mandate

```
+ **NOTE!**: To Verify the Mandate after return from bank page, you must:

1. use `VandarVerify` trait

2. call `verify method` as static

```php
use Vandar\VandarCashier\Traits\VandarVerify;

use VandarVerify;

self::verify(); // Call verify method
```
in your the {callback page} that you added its URL(callback_url) in your Vandar Account to verify and continue the process.


#### #Withdrawal
```php
use Vandar\VandarCashier\VandarWithdrawal;


VandarWithdrawal::list(); // Get the list of Withdrawals

VandarWithdrawal::store(); // Store new withdrawal

VandarWithdrawal::show(); // Show details about specific withdrawal

VandarWithdrawal::cancel(); // Cancel the specific withdrawal

```



#### #Credits

 - Vandar - [vandar.io](https://vandar.io)
