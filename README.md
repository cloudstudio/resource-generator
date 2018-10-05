# Resource Generator tool for Laravel Nova

[![Total Downloads](https://img.shields.io/packagist/dt/cloudstudio/resource-generator.svg?style=flat-square)](https://packagist.org/packages/cloudstudio/resource-generator)

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

Maybe you need link storage folder


```bash
php artisan storage:link
```

### To Do

- [x] Release v1
- [ ] Complete the documentation
- [ ] Decorate generated files properly
- [ ] Refactorize vue code

### Minor Bugs

We have issues with wrong indent when files are generated.


### Documentation


Click <a href="https://krato.github.io/resource-generator-docs/">here</a> for full documentation.


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Eric Lagarda](https://github.com/Krato) 
- [Toni Soriano](https://github.com/cloudstudio)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
