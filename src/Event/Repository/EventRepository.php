<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Repository;


use Shop\Event\Event;
use Shop\Event\EventStream;
use Shop\EventAggregate\EventAggregate;

interface EventRepository
{

    public function saveEventStream(EventStream $stream);

    public function saveEventAggregate(EventAggregate $eventAggregate);

    public function saveEvent(Event $event);

    /**
     * @param string|null $eventFamily Family name filter. If null, returns all events
     * @param array|null $parameters Additional parameters used for searching.
     * @return \Shop\Event\EventStream
     */
    public function find(string $eventFamily = null, array $parameters = null): EventStream;

}