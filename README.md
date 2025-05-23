# Rust-inspired Option object for PHP

TBD

[![PHP Version Support][php-badge]][php]
[![Packagist version][packagist-badge]][packagist]
[![License][license-badge]][license]

[php-badge]: https://img.shields.io/packagist/php-v/tekord/php-option?logo=php&color=8892BF
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/tekord/php-option.svg?logo=packagist
[packagist]: https://packagist.org/packages/tekord/php-option
[license-badge]: https://img.shields.io/badge/license-MIT-green.svg
[license]: https://github.com/tekord/php-option/blob/main/LICENSE-MIT

## Installation

Install the package via Composer:

```bash
composer require tekord/php-option
```

## Usage

Example:

```php
$o = Option::some(200);
$value = $o->unwrap(); // -> 200

$o = Option::none();
$value = $o->unwrap(); // -> ERROR

$o = Option::none();
$value = $o->unwrapOrDefault(50); // -> 50

$o = Option::none();
$value = $o->unwrapOrNull(); // -> null

$o = Option::some("Hello");
$o->isSome(); // true
$o->isNone(); // false

$o = Option::none();
$o->isSome(); // false
$o->isNone(); // true
```

## Testing

```bash
composer test
```
