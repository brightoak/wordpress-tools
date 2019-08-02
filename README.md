# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brightoak/wordpress-custom-post-types.svg?style=flat-square)](https://packagist.org/packages/brightoak/wordpress-custom-post-types)
[![Build Status](https://img.shields.io/travis/brightoak/wordpress-custom-post-types/master.svg?style=flat-square)](https://travis-ci.org/brightoak/wordpress-custom-post-types)
[![Quality Score](https://img.shields.io/scrutinizer/g/brightoak/wordpress-custom-post-types.svg?style=flat-square)](https://scrutinizer-ci.com/g/brightoak/wordpress-custom-post-types)
[![Total Downloads](https://img.shields.io/packagist/dt/brightoak/wordpress-custom-post-types.svg?style=flat-square)](https://packagist.org/packages/brightoak/wordpress-custom-post-types)

This is a tool for doing things in WordPress that are often verbose and should be easier.

## Installation

You can install the package via composer:

```bash
composer require brightoak/wordpress-tools
```

## Usage
#### Custom Post Types:
``` php
add_action('init', function(){
    CustomPostType::init('example')->supports('title', 'editor')->register();
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
