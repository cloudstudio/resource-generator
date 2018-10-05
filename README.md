# Resource Generator tool for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cloudstudio/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/cloudstudio/nova-filemanager)
[![CircleCI branch](https://img.shields.io/circleci/project/github/spatie/nova-tags-field/master.svg?style=flat-square)](https://circleci.com/gh/InfinetyES/Nova-Filemanager)
[![StyleCI](https://github.styleci.io/repos/146585053/shield?branch=master)](https://github.styleci.io/repos/146585053)
[![Total Downloads](https://img.shields.io/packagist/dt/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)

Resource Generator for Laravel Nova

##### Resource Generator Tool preview

![Resource Generator Tool](https://user-images.githubusercontent.com/74367/46522091-12b5ad00-c882-11e8-8ff6-6af312fa2a42.png)


### Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require cloudstudio/resource-generator
```

Next up, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \Cloudstudio\ResourceGenerator\ResourceGenerator(),
    ];
}
```

### Minor Bugs

We have issues with wrong indent when files are generated.


### Documentation

Click <a href="#">here</a> for full documentation.


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Eric Lagarda](https://github.com/Krato) 
- [Toni Soriano](https://github.com/cloudstudio)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
