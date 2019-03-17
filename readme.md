# Money

A basic example implementation of the [Fowler's Money pattern](https://martinfowler.com/eaaCatalog/money.html). The library performs money operations using the currency's smallest unit to prevent rounding errors.

```php
<?php

use Money;

$euro = Money::eur(100);
$tenEuro = $euro + Money::eur(900);

print($tenEuro->format()); // 10,00 €

$shares = $tenEuro->allocate([1,1,1]);

print($shares[0]->format()); //3,34 €
print($shares[1]->format()); //3,33 €
print($shares[2]->format()); //3,33 €
```



## Usage

#### Instantiation

Money amount is represented in currency's smallest units / cents. (e.g. 100 for 1 euro).

```php
$euro = Money::eur(100);

print($euro->getAmount()); // 100
```



## Operations

- add

- subtract

- multiply

- divide


### Add

Sum amount of two money objects using the `add` method.  Addition must be made between objects with the same currency.

```php
$fiveEuro = Money::eur(500);

$tenEuro = $fiveEuro->add($fiveEuro);
```



### Subtract

Subtract amount of two money objects using the `subtract`.  Subtraction must be made between objects with the same currency.

```php
$fiveEuro = Money::eur(500);

$zeroEuro = $fiveEuro->subtract($fiveEuro);
```



### Multiply

Multiply amount using the `multiply` method. 

```php
$fiveEuro = Money::eur(500);

$tenEuro = $fiveEuro->multiply(2);
```



### Divide

Divide amount using the `divide` method. 

```php
$tenEuro = Money::eur(500);

$fiveEuro = $tenEuro->divide(2);
```



## Allocation

- Allocate



### Allocate

Split money amount according to provided ratios. Remaining amount is distributed to shares with the biggest ratios.

```php
$tenEuro = Money::eur(1000);

$shares = $tenEuro->allocate([1,1,1]);

print($shares[0]->getAmount()); //334
print($shares[1]->getAmount()); //333
print($shares[2]->getAmount()); //333
```



## Comparison

### Equals

Compare two money objects using the `equals` method. The method will return false when amount or currency type is the same.

```php
$tenEuro = Money::eur(1000);
$oneEuro = Money::eur(100);
$oneDollar = Money::usd(100);

$oneEuro->equals($tenEuro); // false
$oneEuro->equals($oneDollar); // false
```



### GreaterThan

Check if money amount is larger than the given money amount using the `greaterThan` method.

```php
$tenEuro = Money::eur(1000);
$oneEuro = Money::eur(100);

$tenEuro->greaterThan($oneEuro); // true
```



### GreaterThanOrEqual

Check if money amount is larger or equal to the given money amount  using the `greaterThanOrEqual` method.

```php
$oneEuro = Money::eur(100);

$oneEuro->greaterThanOrEqual($oneEuro); // true
```



### LessThan

Check if money amount is less than the given money amount using the `lessThan` method.

```php
$tenEuro = Money::eur(1000);
$oneEuro = Money::eur(100);

$oneEuro->lessThan($tenEuro); // true
```



### LessThanOrEqual

Check if money amount is lessor equal to the given money amount  using the `lessThanOrEqual` method.

```php
$oneEuro = Money::eur(100);

$oneEuro->lessThanOrEqual($oneEuro); // true
```



## Format

Format the money using the `format` method.

```php
$oneEuro = Money::eur(100);

print($oneEuro->format()); // 1,00 €
```



## Testing

```
phpunit
```



## License

The MIT License (MIT). See the license file for more information.