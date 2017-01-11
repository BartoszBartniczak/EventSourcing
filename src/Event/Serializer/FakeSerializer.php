<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


use BartoszBartniczak\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Event\Event;

/**
 * FakeSerializer is not a real Serializer. In fact it is "in memory" structure, which collects events.
 * As a result of serialization, the hash of the object is returned.
 * For deserialization you need to pass the hash of the object you want to get back.
 * You should use this class only for testing.
 * @package BartoszBartniczak\EventSourcing\Event\Serializer
 */
class FakeSerializer implements Serializer
{
    /**
     * @var ArrayObject
     */
    private $storage;

    /**
     * FakeSerializer constructor.
     */
    public function __construct()
    {
        $this->storage = new ArrayObject();
    }

    /**
     * @param Event $event
     * @return string
     */
    public function serialize(Event $event): string
    {
        $hash = spl_object_hash($event);
        $this->storage->offsetSet($hash, $event);
        return $hash;
    }

    public function deserialize($data): Event
    {
        return $this->storage->offsetGet($data);
    }


}