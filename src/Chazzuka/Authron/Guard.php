<?php namespace Chazzuka\Authron;

use Illuminate\Auth\Guard as LaravelGuard;
use Illuminate\Auth\UserProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Guard extends LaravelGuard {

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param string                                                     $identifier
     * @param \Illuminate\Auth\UserProviderInterface                     $provider
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Symfony\Component\HttpFoundation\Request                  $request
     */
    public function __construct($identifier,
                                UserProviderInterface $provider,
                                SessionInterface $session,
                                Request $request = null)
    {
        parent::__construct($provider, $session, $request);

        $this->identifier = $identifier;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function entity()
    {
        return $this->user();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'login_' . $this->identifier . md5(get_class($this));
    }

    /**
     * @return string
     */
    public function getRecallerName()
    {
        return 'remember_' . $this->identifier . md5(get_class($this));
    }
}