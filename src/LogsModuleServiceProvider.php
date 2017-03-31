<?php namespace Bitsoflove\LogsModule;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Bitsoflove\LogsModule\Log\Contract\LogRepositoryInterface;
use Bitsoflove\LogsModule\Log\LogRepository;
use Bitsoflove\LogsModule\Commands\ExportLogsConsoleCommand;
use Bitsoflove\LogsModule\Commands\ExportLogsCommand;
use Illuminate\Foundation\Application;


class LogsModuleServiceProvider extends AddonServiceProvider
{
    protected $bindings = [
        LogRepositoryInterface::class => LogRepository::class,
    ];

    protected $commands = [
        ExportLogsConsoleCommand::class,
    ];

    protected $listeners = [
        // we might want to define some default listeners here (user logged in / user logged out)
    ];

    public function __construct(Application $app, Addon $addon)
    {
        // allow for listener and binding overrides

        $this->listeners = array_merge($this->listeners, config('bitsoflove.module.logs::logs.listeners'));
        $this->bindings = array_merge($this->bindings, config('bitsoflove.module.logs::logs.bindings'));

        parent::__construct($app, $addon);
    }

    protected $plugins = [];

    protected $routes = [];

    protected $middleware = [];

    protected $aliases = [];

    protected $providers = [];

    protected $singletons = [];

    protected $overrides = [];

    protected $mobile = [];


    public function register()
    {

    }

    public function map()
    {

    }

}
