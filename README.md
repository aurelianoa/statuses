# Laravel Categories

**Stacht Statuses** is a polymorphic Laravel package, for category management. You can categorize any eloquent model with ease, and utilize the power of **Nested Sets**

Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require stacht/categories
```

Publish configuration

```bash
$ php artisan vendor:publish --provider="Stacht\Statuses\StatusesServiceProvider" --tag="config"
```

Publish migration

```bash
$ php artisan vendor:publish --provider="Stacht\Statuses\StatusesServiceProvider" --tag="migrations"
```



## Usage

To add categories support to your eloquent models simply use `\Stacht\Statuses\Traits\Statusable` trait.

### Manage your categories

Your categories are just normal [eloquent](https://laravel.com/docs/master/eloquent) models, so you can deal with it like so.

### Manage your categorizable model

The API is intutive and very straightfoward, so let's give it a quick look:

```php
// Get instance of your model
$post = new \App\Models\Post::find();

// Get attached categories collection
$post->status;

// Get attached categories query builder
$post->status();
```

You can attach categories in various ways:

```php
use Stacht\Statuses\Models\Status;


// Set status by slug
$post->setStatus('test-status');

// Set status by name
$post->setStatus('Status Name');

```

And as you may have expected, you can check if status is set:

```php
use Stacht\Statuses\Models\Status;


// Single status by slug
$post->hasStatus('test-status');

// Single status by name
$post->hasStatus('Status name');

```



###

## Extending

If you need to EXTEND the existing `Category` model note that:

- Your `Category` model needs to extend the `Stacht\Categories\Models\Category` model

If you need to REPLACE the existing `Category` model  you need to keep the following things in mind:

- Your `Category` model needs to implement the `Stacht\Categories\Contracts\Category` contract

In BOTH cases, whether extending or replacing, you will need to specify your new model in the configuration. To do this you must update the `models` value in the configuration file after publishing the configuration with this command:

```
php artisan vendor:publish --provider="Stacht\Categories\CategoriesServiceProvider" --tag="config"
```



## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email corrado.striuli@gmail.com instead of using the issue tracker.

## Credits

- [Aureliano Alarcon][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[link-author]: https://bitbucket.com/stacht
[link-contributors]: ../../contributors
