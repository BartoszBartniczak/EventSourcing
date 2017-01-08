<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Test;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\TestCase\TestCase;

abstract class EventTestCase extends TestCase
{

    /**
     * @var Id
     */
    private $eventId;
    /**
     * @var
     */
    private $dateTime;

    /**
     * @param Event $event
     * @codeCoverageIgnore
     */
    public function assertSameEventIdAsGenerated(Event $event)
    {
        $this->assertSame($this->eventId, $event->getEventId());
    }

    /**
     * @param Event $event
     * @codeCoverageIgnore
     */
    public function assertSameDateTimeAsGenerated(Event $event)
    {
        $this->assertSame($this->dateTime, $event->getDateTime());
    }

    /**
     * @return Id
     */
    protected function generateEventId(): Id
    {
        $this->eventId = new Id(uniqid());
        return $this->eventId;
    }

    /**
     * @return \DateTime
     */
    protected function generateDateTime(): \DateTime
    {
        $this->dateTime = new \DateTime();
        return $this->dateTime;
    }

}