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
- EqualTo
```php
$validator->equalTo(string formFieldName, mixed compareValue);
```
- GreaterThanOrEqual
```php
$validator->greaterThanOrEqual(string formFieldName, mixed compareValue);
```
- GreaterThan
```php
$validator->greaterThan(string formFieldName, mixed compareValue);
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
- LessThanOrEqual
```php
$validator->lessThanOrEqual(string formFieldName, mixed compareValue);
```
- LessThan
```php
$validator->lessThan(string formFieldName, mixed compareValue);
```
- NotEmpty
```php
$validator->notEmpty(string formFieldName);
```
- regex
```php
$validator->regex(string formFieldName, string regex);
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
```php
<?php
use EnderLab\ValidatorInterface;

class MyCustomValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $error = '';

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * SlugValidator constructor.
     *
     * @param string   $fieldName
     * @param mixed    $value
     */
    public function __construct(string $fieldName, $value)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (!empty($this->value)) {
            return true;
        }

        $this->error = 'Field empty !!!';
        return true;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}

$validator = new Validator([
    'email' => 'myemail@host.com',
    'slugProduct' => 'product-slug',
    'productName' => 'Toolbox'
]);

/**
 * $validator->addCustomValidator(
 *     Classname or instance implement ValidatorInterface,
 *     ...arguments
 * );
 */
$validator->addCustomValidator('MyCustomValidator');

if (true == $validator->isValid()) {
    echo('Is valid !!!');
} else {
    print_r($validator->getErrors());
}
```