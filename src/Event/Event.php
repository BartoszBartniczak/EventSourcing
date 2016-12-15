<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event;


use Shop\UUID\UUID;

abstract class Event
{

    /**
     * @var UUID
     */
    protected $eventId;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * Event constructor.
     * @param UUID $eventId
     * @param \DateTime $dateTime
     */
    public function __construct(UUID $eventId, \DateTime $dateTime)
    {
        $this->eventId = $eventId;
        $this->dateTime = $dateTime;
    }

    /**
     * @return UUID
     */
    final public function getEventId(): UUID
    {
        return $this->eventId;
    }

    /**
     * @return \DateTime
     */
    final public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return get_class($this);
    }

    /**
     * @return string
     */
    abstract public function getEventFamilyName(): string;


}