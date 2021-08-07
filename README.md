# Vandar Cashier Package Document
============

This package allows you to access and send your requests to **Vandar APIs** easier.

Just do the following steps to prepare for using.

<br><br><hr><br><br>

#### #Installation

First, install the Cacshier package for Vandar, using the composer package manager:

	composer require vandar\vandar-cashier


<br><br><hr><br><br>


#### #Publish and Database migrations

Vandar Cashier package registers its own database migration directory, so remember to migrate your database after installing the package.

The Vandar Cashier migrations will add some tables into your database to store `Authentications`, `Payments`, `Settlements`, `Mandates` and `Withdrawals`.



At first you need to publish the migrations by using `vendor:publish` artisan command and then migrate it:
```bash
php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=migrations

php artisan migrate
```
<br>

> If you're using Laravel version 5.5+, VandarCashier package will be auto-discovered by Laravel. 

<br>

And if not: register the package in **config/app.php** providers array manually:
```php
'providers' => [
	...
	\Vandar\VandarCashier\VandarCashierServiceProvider::class,
],
```

<br><br><hr><br><br>

#### # Enviroment Variables

For proper package operation, you must define some enviroment variables in `.env` file to be used during the processes:


```php
VANDAR_MOBILE=			//example: VANDAR_MOBILE=09123657458
VANDAR_PASSWORD=		//example: VANDAR_PASSWORD=YerYP%B3
VANDAR_BUSINESS_NAME=		//example: VANDAR_BUSINESS_NAME=sampleBusiness
VANDAR_API_KEY=			//example: VANDAR_API_KEY=7a5cb1b2f87b2c8a9a2
VANDAR_CALLBACK_URL=		//example: VANDAR_CALLBACK_URL=https://www.yourDomainName.com
VANDAR_NOTIFY_URL=		//example: VANDAR_NOTIFY_URL=https://https://www.yourDomainName.com
```

|                     Important Notes 					 					 				|
| 							:---:     						 			    	   		    |
| You can retrieve your Vandar API Key from the [Vandar.Dashboard](https://dash.vandar.io/) |
| CALLBACK_URL must be one of the URLs that you added in your Vandar.Dashboard     			|

<br><br><hr><br><br>

#### #Model
VandarPayment Model and table, use the Polyphorphism relations

| Morph_type             | definition                    | 
| :---:                  |    :----:                     |
| **payable**       	 | person who pay                |
| **paymentable**  	 	 | what the payment was done for |
<br>
So if you want, use one of these traits(As needed) in your Model:

```php
use Vandar\VandarCashier\Traits\Payable
use Payable;

use Vandar\VandarCashier\Traits\Paymentable
use Paymentable;

```

<br><br><hr><br><br>

#### #**USAGE**
For Using all the package method, you just need to use main Vandar file namespace in your files that you use it:
```php
use Vandar\VandarCashier\Vandar;
```

<br>

|                     Important Notes 					 					 		|
| 							:---:     						 			    	    |
| **api_key** will be use from `.env` file 					  			  	        |
| You can pass **callback_url** in the input parameters OR use from `.env` file     |
|The CallBack url must be added in  "callback url list" in your Vandar Account |
| You can pass **notify_url** in the input parameters OR use from `.env` file     |
| In Business methods, you can pass **business** name in the input parameters OR use from `.env` file     |
| To check the payment & mandate status after return from bank page, you Just need to be added checkStatus vandar method in your callback page to check status to continue and verify process.     |
| In the operation of each method, related data will be create or update in their tables!!     |
| Each method that get more than one parameter, should be pass in **Array** format.     |

<br>

<br><br><hr><br><br>

#### #Authentication
In the usage of this package, you don't want to use **token()** method manually no where and all the token operations(generate, refresh, ...) will do automatically but if you want to get token, you can use following method:
```php
Vandar::Auth()->token(); // Get token

```

<br><br><hr><br><br>

#### #IPG

####	 #IPG:pay
| Name               | Type          | Status        |
| :---:              |    :----:     |         :---: |
| amount     	 	 | Integer       | required      |
| callback_url  	 | String        | required <br> (If don't define in `.env` file)     |
| mobile_number      | String        | optional      |
| factor_number      | String        | optional      |
| description        | String        | optional      |
| valid_card_number  | String        | optional      |

<br>

```php
Vandar::IPG()->pay(array $params); // Pass payment parameter that mentioned in the Vandar Document to do the all payment process(generate token, redirect payment page).
```

<br><br>

####	 #IPG:checkStatus

```php
Vandar::CheckStatus(); // Use it in your callback page (callback_url)
```


- **Response when payment is failed from bank payment page**:
```php
[
"token": "Y8ENGSR5WHJSZE6",
"status": "FAILED"
]
```
 
- **Response when payment(transaction) is Successfully**:
```php
[
"status": "SUCCEED",
"amount": "10000.00",
"real_amount": 9500,
"wage": "500",
"trans_id": 162898700786,
"factor_number": null,
"description": null,
"card_number": "610433******6685",
"payment_date": "2021-08-07 23:40:51",
"cid": "9AEEC623JODW5DEEDE3D67CE19BA14A4949CB70E3528E4A85DGT807F09F28753",
"message": "ok",
"mobile_number": null
]
```




<br><br><hr><br><br>



#### #Settlement

#### #Settlement:list
| Name               | Type          | Status        |
| :---:              |    :----:     |         :---: |
| page     	     	 | Integer       | optional      |
| per_page  	     | Integer       | optional      |

```php
Vandar::Settlement()->list(array $params); // Get the list of settlements
```

<br><br>

#### #Settlement:store

| Name               | Type          | Status        |
| :---:              |    :----:     |      :---:    |
| amount     	 	 | Integer       | required      |
| iban  			 | String        | required      |
| track_id     		 | String        | required      |
| payment_number     | Integer       | optional      |
| notify_url         | String        | optional      |

```php
Vandar::Settlement()->store(array $params); // Store new settlement
```

<br><br>

#### #Settlement:show
| Name               | Type          | Status        |
| :---:              |    :----:     |      :---:    |
| settlement_id    	 | String        | optional      |
```php
Vandar::Settlement()->show(string $settlement_id); // Get more details about a specific settlement
```

<br><br>

#### #Settlement:cancel
| Name               | Type          | Status        |
| :---:              |    :----:     |      :---:    |
| transaction_id     | String        | optional      |
```php
Vandar::Settlement()->cancel(int $transaction_id); // Cancel the specific settlement
```



<br><br><hr><br><br>




#### #Billing

#### #Billing:balance
```php
Vandar::Bills()->balance(); // Get the current balance of your business
```
<br><br>

#### #Billing:list
| Name               | Type          | Status        |
| :---:              |    :----:     |      :---:    |
| from_date     	 | String        | optional      |
| to_date  			 | String        | optional      |
| status_kind    	 | String        | optional      |
| status   		     | String        | optional      |
| channel            | String        | optional      |
| formId             | String        | optional      |
| ref_id             | String        | optional      |
| tracking_code      | String        | optional      |
| id                 | String        | optional      |
| track_id           | String        | optional      |
| q                  | String        | optional      |
| per_page           | String        | optional      |
| page               | String        | optional      |
```php
Vandar::Bills()->list(array $params); // Get the list of billing of your business
```


<br><br><hr><br><br>




#### #Business

#### #Business:list

```php
Vandar::Business()->list(); // Get the list of your businesses of your Vandar account
```
<br><br>

#### #Business:info

| Name               | Type          | Status        |
| :---:              |    :----:     |      :---:    |
| business     		 | String        | optional      |
```php
Vandar::Business()->info(string $business); // Show the informations about the specific business
```
<br><br>

#### #Business:users
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| business     		 | String         | optional      |
| page     			 | Integer        | optional      |
| per_page     		 | Integer        | optional      |
```php
Vandar::Business()->users(array $params); // Get the list of busniess users with their informations
```



<br><br><hr><br><br>



#### #Mandate
#### #Mandate:list
```php
Vandar::Mandate()->list(); // Get the list of confirmed Mandates
```
<br><br>

#### #Mandate:store
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| bank_code     	 | String         | required      |
| mobile     		 | String         | required      |
| callback_url     	 | String         | required <br> (If don't define in `.env` file)      |
| count     		 | Integer        | required      |
| limit     		 | Integer        | required      |
| name     			 | String         | optional      |
| email     		 | String         | optional      |
| expiration_date    | date           | optional      |
| wage_type     	 | String         | optional      |
```php
Vandar::Mandate()->store(array $params); // Store new Mandate
```

<br><br>

#### #Mandate:checkStatus
```php
Vandar::CheckStatus(); // Use it in your callback page (callback_url)
```

<br>

- **Successfull mandate creation response**:
```php
[
"token": "afa74040-f7b2-1s4b-a852-3b8sl2373ea",
"authorization_id": "070b2b10-fab3-11sb-ba2a-59sl237ef2d6",
"status": "SUCCEED"
]
```
 
- **Failed mandate creation response**:
```php
[
"token": "afa74040-f7b2-1s4b-a852-3b8sl2373ea",
"status": "FAILED"
]
```

<br><br>

#### #Mandate:show
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| authorization_id   | String         | required      |
```php
Vandar::Mandate()->show(string $authorization_id); // Show the informations of specific mandate
```

<br><br>

#### #Mandate:revoke
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| authorization_id   | String         | required      |
```php
Vandar::Mandate()->revoke(string $authorization_id); // Revoke specific mandate
```




<br><br><hr><br><br>

#### #Withdrawal

#### #Withdrawal:list
```php
Vandar::Withdrawal()->list(); // Get the list of Withdrawals
```
<br><br>

#### #Withdrawal:store
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| authorization_id   | String         | required      |
| amount  			 | String         | required      |
| withdrawal_date    | String         | required <br> (if is_instant=0)     |
| is_instant   		 | integer(0, 1)  | optional      |
| notify_url   		 | String         | optional <br> (If don't define in `.env` file)     |
| max_retry_count    | integer	      | optional      |
```php
Vandar::Withdrawal()->store(array $params); // Store new withdrawal
```
<br><br>

#### #Withdrawal:show
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| withdrawal_id  	 | String         | required      |
```php
Vandar::Withdrawal()->show(string $withdrawal_id); // Show details about specific withdrawal
```
<br><br>

#### #Withdrawal:cancel
| Name               | Type           | Status        |
| :---:              |    :----:      |      :---:    |
| withdrawal_id  	 | String         | required      |
```php
Vandar::Withdrawal()->cancel(string $withdrawal_id); // Cancel the specific withdrawal
```

<br><br><hr><br><br>

#### #Credits

 - Vandar - [vandar.io](https://vandar.io)
