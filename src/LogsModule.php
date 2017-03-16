<?php namespace Bitsoflove\LogsModule;

use Anomaly\Streams\Platform\Addon\Module\Module;

class LogsModule extends Module
{
    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'logs' => [
            'buttons' => [
                'export' => [
                    'href' => '/admin/export/logs',
                    'text' => 'Export',
                    'type' => 'info',
                    'icon' => 'fa fa-download'
                ]
            ]
        ]
    ];
}
