# Bright Oak WordPress Tools
[![Latest Version on Packagist](https://img.shields.io/packagist/v/brightoak/ordpress-tools.svg?style=flat-square)](https://packagist.org/packages/brightoak/wordpress-tools)
[![Build Status](https://img.shields.io/scrutinizer/build/g/brightoak/wordpress-tools)](https://travis-ci.org/brightoak/wordpress-tools)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/brightoak/wordpress-tools?style=flat-square)](https://scrutinizer-ci.com/g/brightoak/wordpress-tools)
[![Total Downloads](https://img.shields.io/packagist/dt/brightoak/wordpress-tools.svg?style=flat-square)](https://packagist.org/packages/brightoak/wordpress-tools)


This is a tool for doing things in WordPress that are often verbose and should be easier.

## Installation

You can install the package via composer:

```bash
composer require brightoak/wordpress-tools
```

## Usage
#### Custom Post Types:
Simple example:
``` php
add_action('init', function(){
    CustomPostType::init('example')->setSupports('title', 'editor')->register();
});
```

Elaborate example from https://codex.wordpress.org/Function_Reference/register_post_type
``` php
add_action('init', function(){
    CustomPostType::init('book')
    ->setOptions('description' => 'Description', 'has_archive' => true])
    ->setSupports('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
    ->register();
});
```
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rory@brightoak.com instead of using the issue tracker.

## Credits

- [Rory McDaniel](https://github.com/rorymcdaniel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
