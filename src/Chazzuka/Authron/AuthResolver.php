<?php namespace Chazzuka\Authron;

use Illuminate\Foundation\Application;

class AuthResolver {

    /**
     * Array of registered authentication managers
     *
     * @var array
     */
    protected $managers = [];

    /**
     * Identifier of default authentication manager
     *
     * @var string
     */
    protected $default;

    /**
     * @param \Illuminate\Foundation\Application $app
     * @param array                              $configurations
     * @param string                             $default
     */
    public function __construct(Application $app, array $configurations, $default = null)
    {
        foreach ($configurations as $identifier => $options)
        {
            $this->managers[$identifier] = new AuthManager($options, $identifier, $app);
        }

        $this->default = $default;
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string   $driver
     * @param  \Closure $callback
     *
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        foreach ($this->managers as $identifier => $manager)
        {
            $manager->extend($driver, $callback);
        }

        return $this;
    }

    /**
     * Get default authentication manager's id
     *
     * @return string
     */
    public function getDefaultId()
    {
        return $this->default;
    }

    /**
     * Set default authentication manager
     *
     * @param string $id
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setDefaultId($id)
    {
        if (array_key_exists($id, $this->managers))
        {
            $this->default = $id;
        }

        throw new \InvalidArgumentException("authorization manager {$id} does not exists");
    }

    /**
     * Magic call method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($name, array $arguments = [])
    {
        if (array_key_exists($name, $this->managers))
        {
            return $this->managers[$name];
        }

        if ($this->default)
        {
            return call_user_func_array([$this->managers[$this->default], $name], $arguments);
        }

        throw new \BadMethodCallException(__CLASS__." has no method {$name}");
    }

}