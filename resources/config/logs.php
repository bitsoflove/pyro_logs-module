<?php return [

    // This is just the default config file
    // Do not edit it directly,
    // Instead publish it and edit it at resources/{your-project}/addons/bitsoflove/logs-module/config/logs.php


    // reference your extended Log Model (used in LogTableBuilder )
    'model' => \Bitsoflove\LogsModule\Log\LogModel::class, //\App\Overrides\Log\Model\ExtendedLogModel::class,

    // configure any overridden bindings (used in LogModuleServiceProvider )
    'bindings' => [
        // You might want to extend the default LogModel here
        \Bitsoflove\LogsModule\Log\Contract\LogInterface::class => \Bitsoflove\LogsModule\Log\LogModel::class,

        // Maybe you want a different export behaviour ?
        \Bitsoflove\LogsModule\Commands\ExportLogsCommandInterface::class => \Bitsoflove\LogsModule\Commands\ExportLogsCommand::class,
    ],

    // define the event handlers per event. Handlers are responsible for creating logs.
    'listeners' => [
        \Anomaly\UsersModule\User\Event\UserWasLoggedIn::class => [
            \Bitsoflove\LogsModule\Listeners\UserLoggedInListener::class
        ],
    ],

    // Configure the log table
    'table' => [
        'columns' => ['slug', 'message'],
        'middleware' => [],
    ],
];