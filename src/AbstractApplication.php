<?php

namespace Empress;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Server;
use Amp\Http\Server\ServerObserver;
use Amp\Promise;
use Amp\Success;
use Empress\Routing\RouteConfigurator;

/**
 * Defines an application object that will be run against http-server.
 * Since it implements the ServerObserver interface it has two
 * lifecycle methods - onStart() and onStop() that can be used
 * when the application is booted and shut down respectively.
 */
abstract class AbstractApplication implements ServerObserver
{

    /**
     * Defines routes for the application.
     *
     * @return \Empress\Routing\RouteConfigurator
     */
    abstract public function configureRoutes(): RouteConfigurator;

    /**
     * Configures the application instance before the server starts.
     *
     * @return ApplicationConfigurator
     */
    abstract public function configureApplication(): ApplicationConfigurator;

    /** @inheritDoc */
    public function onStart(Server $server): Promise
    {
        return new Success();
    }

    /** @inheritDoc */
    public function onStop(Server $server): Promise
    {
        return new Success();
    }
}
