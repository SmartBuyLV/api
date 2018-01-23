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
$api = new Smartbuy\Api\Api($accessToken, $cookieLifetimeInDays);
$api->init(); 
```

Register order
```php
$api = new Admitad\Api\Api($accessToken, $cookieLifetimeInDays);
$api->registerOrder($orderUniqueNumber, $fullAmount, $rateId); // RateId is optional
); 
```

