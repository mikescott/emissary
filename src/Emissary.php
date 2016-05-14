<?php
namespace mikescott\Emissary;

use Illuminate\Container\Container;
use Slim\App;

class Emissary extends Container {
    protected $app;
    protected $container;
    protected $providers = [];
    
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
        $this->bootProviders();
    }

    public function getApp()
    {
        return $this->app;
    }

    /**
     * Boots all of the service providers
     */
    public function bootProviders()
    {
        foreach($this->providers as $provider) {
            $provider->boot();
        }
    }

    /**
     * Adds service providers from an array, like those in Laravel's config/app.php
     * @param array $providers
     */
    public function addProviders(array $providers)
    {
        foreach($providers as $provider) {
            $p = new $provider($this);
            $p->register();
            $this->providers[] = $p;
        }
    }
    
    public function addAliases(array $aliases)
    {
        foreach($aliases as $alias => $original) {
            class_alias($original, $alias);
        }
    }

    /**
     * Binds all of the service providers to both the Illuminate container and Slim's container
     * @param array|string $abstract
     * @param null $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        parent::bind($abstract, $concrete, $shared);

        $emissary = $this;

        $this->container[$abstract] = function($c) use($emissary, $abstract) {
            return $emissary->make($abstract);
        };
    }
}