<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;
use Shop\User\Event\UserHasBeenLoggedOut as UserHasBeenLoggedOutEvent;

class UserHasBeenLoggedOut extends Handler
{
    /**
     * @param Event|UserHasBeenLoggedOutEvent $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        return $this->serializeUserEventToArray($event);
    }

    public function deserialize(array $data): Event
    {
        $eventData = $this->deserializeUserEventArray($data);

        return new UserHasBeenLoggedOutEvent(
            $eventData[self::$EVENT_ID],
            $eventData[self::$EVENT_DATE_TIME],
            $eventData[self::$USER_PROPERTY]['email']
        );
    }

}