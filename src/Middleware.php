<?php
namespace mikescott\Emissary;

use Illuminate\Support\Facades\Facade;

class Middleware {
    protected $providers;
    protected $aliases;

    public function __construct($providers = [], $aliases = []) {
        $this->providers = $providers;
        $this->aliases = $aliases;
    }

    public function __invoke($request, $response, $next)
    {
        $emissary = new Emissary($next);
        $emissary->addProviders(array_merge(['mikescott\Emissary\ConfigServiceProvider'], $this->providers));

        Facade::setFacadeApplication($next->getContainer());
        $emissary->addAliases($this->aliases);

        return $next($request, $response);
    }
}