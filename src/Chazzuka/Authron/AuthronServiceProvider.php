<?php namespace Chazzuka\Authron;

use Illuminate\Support\ServiceProvider;

class AuthronServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('auth', function ($app)
        {
            $app['auth.loaded'] = true;

            $config = $app['config']->get('auth.resolver');
            $default = array_pull($config, 'default');

            return new AuthResolver($app, $config, $default);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['auth'];
	}

}
