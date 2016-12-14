<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event\Serializer\Handler;


use Shop\User\Event\Event as UserEvent;

abstract class Handler extends \Shop\Event\Serializer\Handler
{
    protected static $USER_PROPERTY = 'user';

    /**
     * @param UserEvent $event
     * @return array
     */
    protected function serializeUserEventToArray(UserEvent $event): array
    {
        $serializedArray = $this->serializeEventHeaderToArray($event);
        $serializedArray[self::$USER_PROPERTY] = [
            'email' => $event->getUserEmail(),
        ];
        return $serializedArray;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function deserializeUserEventArray(array $data): array
    {
        $deserializedData = $this->deserializeEventHeader($data);
        $deserializedData[self::$USER_PROPERTY] =
            [
                'email' => $data[self::$USER_PROPERTY]['email']
            ];

        return $deserializedData;
    }

}