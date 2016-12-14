<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;
use Shop\User\Event\UserAccountHasBeenActivated as AttemptOfActivatingUserAccountEvent;

class UserAccountHasBeenActivated extends Handler
{
    /**
     * @param Event|AttemptOfActivatingUserAccountEvent $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $serializedData = $this->serializeUserEventToArray($event);
        $serializedData['activation_token'] = $event->getActivationToken();
        return $serializedData;
    }

    /**
     * @param array $data
     * @return Event
     */
    public function deserialize(array $data): Event
    {
        $header = $this->deserializeUserEventArray($data);

        return new AttemptOfActivatingUserAccountEvent(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            $header[self::$USER_PROPERTY]['email'],
            $data['activation_token']
        );
    }


}