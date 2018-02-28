smartbuy-api
==================

A PHP wrapper around the SmartBuy API

Install
-------

Install http://getcomposer.org/ and run the following command:

```
php composer.phar require smartbuy/api
```

Examples
-------

#### Methods
There are 2 common methods to communicate with api:

Checks if have query parameter with affiliate ID and sets cookie
```php
$api = new Smartbuy\Api\Api($accessToken);
$api->init(); 
```
Register new order
```php

$products = [
    [
        'title' => 'Product #1',
        'amount' => 10
    ],
    [
        'title' => 'Product #2',
        'amount' => 4,
        'rate' => 'SB1'
    ]
];

$api = new Smartbuy\Api\Api($accessToken);
$api->orderRegister($orderUniqueNumber, $products);
```

Cancel existing order
```php
$api = new Smartbuy\Api\Api($accessToken);
$api->orderCancel($orderUniqueNumber);
```

Delete existing order
```php
$api = new Smartbuy\Api\Api($accessToken);
$api->orderDelete($orderUniqueNumber);
```

*DEPRECATED* Register order (Executes only if have detected affiliate ID)
```php
$api = new Smartbuy\Api\Api($accessToken);
$api->registerOrder($orderUniqueNumber, $fullAmount, $rateId); // RateId is optional
```

