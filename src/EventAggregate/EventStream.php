<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\EventAggregate;


use Shop\ArrayObject\ArrayOfObjects;
use Shop\Event\Event;

class EventStream extends ArrayOfObjects
{

    /**
     * EventStream constructor.
     * @param array|null $items
     */
    public function __construct(array $items = null)
    {
        parent::__construct(Event::class, $items);
    }

}