<?php
namespace mikescott\Emissary;

use Illuminate\Support\Facades\Facade;

class Middleware {
    protected $providers;
    protected $aliases;
    protected $app;

    public function __construct($app, $providers = [], $aliases = []) {
        $this->providers = $providers;
        $this->aliases = $aliases;
        $this->app = $app;
    }

    public function __invoke($request, $response, $next)
    {
        $emissary = new Emissary($this->app);
        $emissary->addProviders(array_merge(['mikescott\Emissary\ConfigServiceProvider'], $this->providers));

        Facade::setFacadeApplication($this->app->getContainer());
        $emissary->addAliases($this->aliases);

        return $next($request, $response);
    }
}
