<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;

class UserHasBeenLoggedIn extends Handler
{
    /**
     * @param Event|\Shop\User\Event\UserHasBeenLoggedIn $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        return $this->serializeUserEventToArray($event);
    }

    public function deserialize(array $data): Event
    {
        $eventArray = $this->deserializeUserEventArray($data);

        return new \Shop\User\Event\UserHasBeenLoggedIn(
            $eventArray[self::$EVENT_ID],
            $eventArray[self::$EVENT_DATE_TIME],
            $eventArray[self::$USER_PROPERTY]['email']
        );
    }


}