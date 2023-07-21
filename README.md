# Running a Laravel application

[![theories](https://img.shields.io/badge/more-theories-purple)](https://github.com/stars/szepeviktor/lists/theory)

list features -> orgranize them into categories, see GH issue!

ErrorDocument 500 /errors/HTTP_INTERNAL_SERVER_ERROR.html
ErrorDocument 503 /errors/HTTP_SERVICE_UNAVAILABLE.html
https://templates.mailchimp.com/resources/inline-css/

mysqldump --routines --triggers --events


## Setting up GitHub repository

- See [GitHub repository inspection](https://github.com/szepeviktor/github-repository-inspection)
- The first commit must be a tagged release of `laravel/laravel`

## Entry points

Startup methods.

1. Web - through `public/index.php`
2. CLI - through `artisan`
3. Queue workers - through `artisan queue:work`
4. Cron job - through `artisan schedule:run`

## Security

- HTTP method not in routes
- HTTP 404
- CSRF token mismatch (Google Translate, Facebook app, Google Search "Cached")
- Failed login attempts
- Requests for .php files
- Non-empty hidden field in forms

### Security Exceptions

```php
protected $securityExceptions = [
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
];
```

## Caches

Use [Redis PECL extension](https://laravel.com/docs/5.6/redis#phpredis) instead of Predis,
and the [key hash tag](https://laravel.com/docs/5.6/queues#driver-prerequisites).

- Compiled classes `/bootstrap/cache/compiled.php`
  [removed in 5.4](https://github.com/laravel/framework/commit/09964cc8c04674ec710af02794f774308a5c92ca#diff-427cac03b212e5fd24785d55149d3aea)
- Services `/bootstrap/cache/services.php` - flushed in composer script `post-autoload-dump`
- Discovered packages `/bootstrap/cache/packages.php` - flushed in composer script `post-autoload-dump`
- Configuration cache `/bootstrap/cache/config.php` - flushed by `artisan config:clear`
- Routes cache `/bootstrap/cache/routes.php` - flushed by `artisan route:clear`
- Events cache `/bootstrap/cache/events.php` - flushed by `artisan event:clear`
- Application cache (`CACHE_DRIVER`) - flushed by `artisan cache:clear`
- Blade templates cache `/storage/framework/views/*.php` - flushed by `artisan view:clear`

See `/vendor/laravel/framework/src/Illuminate/Foundation/Application.php`

Caching depends on `APP_ENV` variable.

## Static analysis

- [Larastan](https://github.com/nunomaduro/larastan) a PHPStan wrapper for Laravel.
- [Bladestan](https://github.com/TomasVotruba/bladestan)
- [more](https://github.com/stars/szepeviktor/lists/static-analysis)

## Throttle 404 errors

`routes/web.php`

```php
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Throttle 404 errors
Route::fallback(static function () {
    throw new NotFoundHttpException();
})->middleware('throttle:10,1');
```

## Is Laravel down?

1. `./artisan tinker --execute='printf("Application is %s.",App::isDownForMaintenance()?"down":"up");'`
1. `ls storage/framework/down`
1. `./artisan isdown` - using `Commands/IsDownForMaintenance.php`

## Check route methods

`./artisan route:check` - using `Commands/RouteCheckCommand.php`

## Check PHP version after PHP upgrade

Insert this line in the top of `bootstrap/app.php`

```php
if (phpversion() !== '8.2.7') dd('Different PHP version:', phpversion());
```

Check all four entry points!

## Debugging

https://github.com/spatie/ray

Add the following to `app/Providers/AppServiceProvider.php`

```php
    public function boot(): void
    {
        \DB::listen(function ($query) {
            \info('SQL query: ' . $query->sql);
        });
    }
```

## URL categories

- Root files - stored in `public/` and `public/.well-known/` directories
- Static assets - stored in various subdirectories of `public/`
- User generated media - stored in `storage/app/public/` directory
- Virtual URL-s are handled by Laravel started in `public/index.php`
- Explicit API calls prefixed with `/api/` in URL path

## Nova

Custom exception handling
https://nova.laravel.com/docs/4.0/installation.html#error-reporting
