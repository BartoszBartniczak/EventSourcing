<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\Event\Event;
use Shop\User\Event\ActivationTokenHasBeenGenerated as ActivationTokenHasBeenGeneratedEvent;

class ActivationTokenHasBeenGenerated extends Handler
{
    /**
     * @param Event|ActivationTokenHasBeenGeneratedEvent $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $serializedData = $this->serializeUserEventToArray($event);
        $serializedData['activation_token'] = $event->getActivationToken();
        return $serializedData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeUserEventArray($data);

        return new ActivationTokenHasBeenGeneratedEvent(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            $header[self::$USER_PROPERTY]['email'],
            $data['activation_token']
        );
    }


}