<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;


use Shop\Event\Event;
use Shop\UUID\UUID;

abstract class Handler
{

    public static $EVENT_NAME = 'name';
    public static $EVENT_ID = 'eventId';
    public static $EVENT_DATE_TIME = 'eventDateTime';
    public static $EVENT_FAMILY = 'eventFamily';
    private static $DATE_TIME_FORMAT = 'Y-m-d H:i:s.u';

    public abstract function serialize(Event $event): array;

    public abstract function deserialize(array $data): Event;

    protected function serializeEventHeaderToArray(Event $event): array
    {
        return [
            self::$EVENT_ID => $event->getUuid()->toNative(),
            self::$EVENT_FAMILY => $event->getEventFamilyName(),
            self::$EVENT_NAME => get_class($event),
            self::$EVENT_DATE_TIME => [
                'dateTime' => $event->getDateTime()->format(self::$DATE_TIME_FORMAT),
                'format' => self::$DATE_TIME_FORMAT,
                'timezone' => $event->getDateTime()->getTimezone()->getName()
            ],
        ];
    }

    protected function deserializeEventHeader(array $event): array
    {
        return [
            self::$EVENT_ID => new UUID($event[self::$EVENT_ID]),
            self::$EVENT_NAME => $event[self::$EVENT_NAME],
            self::$EVENT_DATE_TIME => \DateTime::createFromFormat($event[self::$EVENT_DATE_TIME]['format'], $event[self::$EVENT_DATE_TIME]['dateTime'], new \DateTimeZone($event[self::$EVENT_DATE_TIME]['timezone'])),
        ];
    }

}