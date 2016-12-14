<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;

class UnsuccessfulAttemptOfActivatingUserAccount extends Handler
{

    /**
     * @param Event|\Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $serializedData = $this->serializeUserEventToArray($event);
        $serializedData['activation_token'] = $event->getActivationToken();
        $serializedData['message'] = $event->getMessage();
        return $serializedData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeUserEventArray($data);

        return new \Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            $header[self::$USER_PROPERTY]['email'],
            $data['activation_token'],
            $data['message']
        );
    }


}