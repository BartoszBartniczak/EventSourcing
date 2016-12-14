<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;
use Shop\User\Event\UserHasBeenRegistered as UserHasBeenRegisteredEvent;

class UserHasBeenRegistered extends Handler
{
    /**
     * @param Event|UserHasBeenRegisteredEvent $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $userData = $this->serializeUserEventToArray($event);
        $userData['password'] = [
            'hash' => $event->getPasswordHash(),
            'salt' => $event->getPasswordSalt()
        ];
        return $userData;
    }


    public function deserialize(array $data): Event
    {
        $userEventArray = $this->deserializeUserEventArray($data);

        return new UserHasBeenRegisteredEvent(
            $userEventArray[self::$EVENT_ID],
            $userEventArray[self::$EVENT_DATE_TIME],
            $userEventArray[self::$USER_PROPERTY]['email'],
            $data['password']['hash'],
            $data['password']['salt']
        );
    }


}