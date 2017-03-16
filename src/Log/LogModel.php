<?php namespace Bitsoflove\LogsModule\Log;

use Anomaly\UsersModule\User\UserModel;
use Bitsoflove\LogsModule\Log\Contract\LogInterface;
use Anomaly\Streams\Platform\Model\Logs\LogsLogsEntryModel;

class LogModel extends LogsLogsEntryModel implements LogInterface
{
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
    }

    protected $casts = [
        'data' => 'array',
    ];

    public function user() {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
