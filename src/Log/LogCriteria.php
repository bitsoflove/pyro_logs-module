<?php
namespace Bitsoflove\LogsModule\Log;

use Anomaly\Streams\Platform\Entry\EntryCriteria;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Illuminate\Database\Query\Builder;

class LogCriteria extends EntryCriteria
{

    public function __construct(Builder $query, StreamInterface $stream, $method)
    {
        parent::__construct($query, $stream, $method);
    }

    public function sorted($direction = 'ASC')
    {
        $this->query->orderBy('created_at', 'DESC');
    }

}
