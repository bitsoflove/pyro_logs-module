<?php namespace Bitsoflove\LogsModule\Listeners;

use Bitsoflove\LogsModule\Log\Contract\LogRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

abstract class AbstractListener
{
    protected $user_id;

    protected $logRepository = null;

    public function __construct()
    {
        $this->logRepository = app(LogRepositoryInterface::class);
        $this->user_id = empty(Auth::user()) ? null : Auth::user()->id;
    }

    public function handle($event) {
        try {
            $this->handleEvent($event);
        } catch(\Exception $e) {
            Log::error($e);
        }
    }

    public abstract function handleEvent($event);
}
