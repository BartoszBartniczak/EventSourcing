<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event\Serializer\Handler;


use Shop\Email\Email;
use Shop\Email\Event\EmailHasNotBeenSent as EmailHasNotBeenSentEvent;
use Shop\Event\Event;
use Shop\Event\Serializer\Handler;

class EmailHasNotBeenSent extends Handler
{
    /**
     * @param Event|EmailHasNotBeenSentEvent $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $serializedData = $this->serializeEventHeaderToArray($event);
        $serializedData ['email'] = [
            'id' => $event->getEmail()
        ];
        $serializedData['errorMessage'] = $event->getErrorMessage();
        return $serializedData;
    }

    /**
     * @param array $data
     * @return Event
     */
    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);
        return new EmailHasNotBeenSentEvent(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            new Email($data['email']['id']),
            $data['errorMessage']
        );
    }


}