<?php

return [
    'admin/logs' => [
        'as' => 'admin.logs.table',
        'verb' => 'GET',
        'middleware' => config('bitsoflove.module.logs::logs.table.middleware'),
        'uses' => 'Bitsoflove\LogsModule\Http\Controller\Admin\LogsController@index'
    ],


//    'entry/handle/export/bitsoflove.module.logs' => [
      'admin/export/logs' => [
        'as' => 'admin.logs.export',
        'uses' => 'Bitsoflove\LogsModule\Http\Controller\Admin\LogsController@export',
    ],
];
