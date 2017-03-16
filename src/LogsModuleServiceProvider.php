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
    public function __construct(Application $app, Addon $addon)
    {
        $this->listeners = $this->listeners + config('bitsoflove.module.logs::logs.listeners');
        $this->bindings = $this->bindings + config('bitsoflove.module.logs::logs.bindings');

        parent::__construct($app, $addon);
    }

    protected $plugins = [];

    protected $commands = [
        ExportLogsConsoleCommand::class,
    ];

    protected $routes = [];

    protected $middleware = [];

    protected $listeners = [];

    protected $aliases = [];

    protected $bindings = [
        LogRepositoryInterface::class => LogRepository::class,
    ];

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
