<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Repository;


use Shop\Event\Event;
use Shop\Event\Serializer\Serializer as EventSerializer;
use Shop\EventAggregate\EventAggregate;
use Shop\EventAggregate\EventStream;

class InMemoryEventRepository implements EventRepository
{
    /**
     * @var array
     */
    public static $memory;

    /**
     * @var EventSerializer
     */
    protected $eventSerializer;

    /**
     * InMemoryEventRepository constructor.
     * @param EventSerializer $serializer
     */
    public function __construct(EventSerializer $serializer)
    {
        if (!is_array(self::$memory)) {
            self::$memory = [];
        }

        $this->eventSerializer = $serializer;
    }

    public function save(EventAggregate $eventAggregate)
    {
        $this->saveEventStream($eventAggregate->getUncommitedEvents());
    }

    protected function saveEventStream(EventStream $stream)
    {
        foreach ($stream as $event) {
            $this->saveEvent($event);
        }
    }

    protected function saveEvent(Event $event)
    {
        self::$memory[$event->getEventFamilyName()][] = $this->eventSerializer->serialize($event);
    }

    public function find(string $eventFamily, string $objectId): EventStream
    {
        return new EventStream($this->deserializeEvents(self::$memory[$eventFamily]));
    }

    /**
     * @param array $serializedEvents
     * @return Event[]
     */
    private function deserializeEvents(array $serializedEvents): array
    {
        $events = [];
        foreach ($serializedEvents as $serializedEvent) {
            $events[] = $this->eventSerializer->deserialize($serializedEvent);
        }
        return $events;
    }


}