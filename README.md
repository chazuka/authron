Authron
---
add multi tenant auth configuration at `config/auth.php`

```php
    'resolver' => [
    	'default' => 'member',
        'member' => [
            'driver' => 'eloquent',
            'model' => 'App\Account\Member',
        ],
        'audience' => [
            'driver' => 'audience',
            'model' => 'App\Audiences\Audience',
        ],
    ],
```

Replace laravel auth provider in `config/app.php` with `Chazzuka\Authron\AuthronServiceProvider`

```php
// login with default auth manager
Auth::attempt($crendetials); 
Auth::guest();
Auth::check();

// above is equivalent to
Auth::member()->attempt($credentials);
Auth::member()->guest();
Auth::member()->check();

// login audience
Auth::audience()->attempt($credentials);
Auth::audience()->guest();
Auth::audience()->check();
```

Register custom user provider

```php
// Register only for audience auth manager
$this->app['auth']->audience()->extend('audience', function ()
{
    $provider = new AudienceProvider($this->app['audiences']);

    return new Guard('audience', $provider, $this->app['session.store']);
});

// register for all registered managers
$this->app['auth']->extend('audience', function ()
{
    $provider = new AudienceProvider($this->app['audiences']);

    return new Guard('audience', $provider, $this->app['session.store']);
});
```

