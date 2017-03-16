<?php namespace Bitsoflove\LogsModule\Listeners;

use Anomaly\UsersModule\User\Event\UserWasLoggedIn;
use Bitsoflove\LogsModule\Log\Contract\LogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserLoggedInListener extends AbstractListener
{
    public function handleEvent($event)
    {
        $eventDefinition = UserWasLoggedIn::class;

        $user = $event->getUser();
        $name = $user->first_name . " " . $user->last_name;

        $this->logRepository->create([
            'event' => $eventDefinition,
            'slug' => 'login',
            'message' => "$name logged in",
            'user_id' => $user->id,
            'data' => null,
        ]);
    }
}
