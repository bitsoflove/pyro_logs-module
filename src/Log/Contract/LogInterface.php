<?php namespace Bitsoflove\LogsModule\Log\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

interface LogInterface extends EntryInterface
{
    public function user();
}
