<?php namespace Chazzuka\Authron;

use Illuminate\Auth\AuthManager as LaravelAuthManager;
use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\Guard as LaravelGuard;
use Illuminate\Foundation\Application;

class AuthManager extends LaravelAuthManager {

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @param array                              $options
     * @param string                             $identifier
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(array $options, $identifier, Application $app)
    {
        parent::__construct($app);

        $this->options = $options;

        $this->identifier = $identifier;
    }

    protected function callCustomCreator($driver)
    {
        $custom = parent::callCustomCreator($driver);

        if ($custom instanceof Guard) return $custom;

        if ($custom instanceof LaravelGuard) $custom = $custom->getProvider();

        return new Guard($this->identifier, $custom, $this->app['session.store']);
    }

    public function createDatabaseDriver()
    {
        return new Guard($this->identifier, $this->createDatabaseProvider(), $this->app['session.store']);
    }

    protected function createDatabaseProvider()
    {
        return new DatabaseUserProvider($this->app['db']->connection(), $this->app['hash'], $this->options['table']);
    }

    public function createEloquentDriver()
    {
        return new Guard($this->identifier, $this->createEloquentProvider(), $this->app['session.store']);
    }

    protected function createEloquentProvider()
    {
        return new EloquentUserProvider($this->app['hash'], $this->options['model']);
    }

    public function getDefaultDriver()
    {
        return $this->options['driver'];
    }

    public function setDefaultDriver($name)
    {
        $this->options['driver'] = $name;
    }

}