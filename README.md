# Validator

[![Build Status](https://travis-ci.org/ender9108/Validator.svg?branch=master)](https://travis-ci.org/ender9108/Validator)
[![Coverage Status](https://coveralls.io/repos/github/ender9108/Validator/badge.svg?branch=master)](https://coveralls.io/github/ender9108/Validator?branch=master)

## Get started
```php
<?php
$validator = new Validator([
    'email' => 'myemail@host.com',
    'slugProduct' => 'product-slug',
    'productName' => 'Toolbox'
]);

// add default validator
$validator->email('email')
          ->slug('slugProduct')
          ->length('firstname', 5, 64);

if (true == $validator->isValid()) {
    echo('Is valid !!!');
} else {
    print_r($validator->getErrors());
}
```

## Default validator list

- Boolean
```php
$validator->boolean(string formFieldName);
```
- Datetime
```php
$validator->datetime(string formFieldName [, string dateFormat = Y-m-d H:i:s]);
```
- Email
```php
$validator->email(string formFieldName);
```
- Int
```php
$validator->int(string formFieldName [,int min = null, int max = null]);
```
- Ip
```php
$validator->ip(string formFieldName [, bool isIpv6 = false]);
```
- Length
```php
$validator->length(string formFieldName [,int min = null, int max = null]);
```
- NotEmpty
```php
$validator->notEmpty(string formFieldName);
```
- Slug
```php
$validator->slug(string formFieldName);
```
- Url
```php
$validator->url(string formFieldName [, int flags = null]);
```

## Add custom validator