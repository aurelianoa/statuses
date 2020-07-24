# Laravel Statuses

**Stacht Statuses** is a polymorphic Laravel package for status management.

Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require stacht/statuses
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

To add status support to your eloquent models simply use `\Stacht\Statuses\Traits\Statusable` trait.

### Manage your statuses

Your statuses are just normal [eloquent](https://laravel.com/docs/master/eloquent) models, so you can deal with it like so.

### Manage your statusable model

The API is intutive and very straightfoward, so let's give it a quick look:

```php
// Get instance of your model
$post = new \App\Models\Post::find();

// Get latest status by magic getter
$post->status;
// Or simply use the method
$post->status();

// Both are implementing the:
$post->latestStatus()


// Get all attached statuses
$post->statuses;

// Get query builder of statuses
$post->statuses();
```

You can attach statuses in various ways:

```php
use Stacht\Statuses\Models\Status;


// Set status by slug
$post->setStatus('test-status');

// Set status by name
$post->setStatus('Status Name');

```

Get models including one or more statuses

```php
$posts->currentStatus('open', 'active', ..)->get();
```

Get models excluding one or more statuses

```php
$posts->otherCurrentStatus('draft', 'archived', ...)->get();
```



###

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
