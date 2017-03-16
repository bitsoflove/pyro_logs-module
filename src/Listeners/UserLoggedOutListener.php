<?php namespace Bitsoflove\LogsModule\Listeners;

use Anomaly\UsersModule\User\Event\UserWasLoggedOut;
use Bitsoflove\LogsModule\Log\Contract\LogRepositoryInterface;

class UserLoggedOutListener extends AbstractListener
{
    public function handleEvent($event)
    {
        $eventDefinition = UserWasLoggedOut::class;

        $user = $event->getUser();
        $name = $user->first_name . " " . $user->last_name;

        $repo = app (LogRepositoryInterface::class);
        $repo->create([
            'event' => $eventDefinition,
            'slug' => 'logout',
            'message' => "$name logged out",
            'user_id' => $user->id,
            'data' => null,
        ]);

    }
}
