<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event;


use Shop\ArrayObject\ArrayOfObjects;

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