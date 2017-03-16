<?php

use Anomaly\Streams\Platform\Database\Migration\Migration;

class BitsofloveModuleLogsCreateLogsStream extends Migration
{

    protected $fields = [
        'event' => [
            'type' => 'anomaly.field_type.text',
        ],
        'slug' => [
            'type' => 'anomaly.field_type.text',
        ],
        'data' => [
            'type' => 'anomaly.field_type.text',
        ],
        'message' => [
            'type' => 'anomaly.field_type.text',
        ],
        'user_id' => [
            'type' => 'anomaly.field_type.text',
        ],
    ];
    protected $stream = [
        'slug' => 'logs',
        'title_column' => 'message',

        'translatable' => false,
        'searchable'   => true,
        'trashable'    => true,
        'sortable'     => true,
    ];

    /**
     * The stream assignments.
     *
     * @var array
     */
    protected $assignments = [
        'event' => [
            'required' => true,
        ],
        'slug' => [
            'required' => true,
        ],
        'data' => [

        ],
        'message' => [

        ],
        'user_id' => [

        ],
    ];

}
