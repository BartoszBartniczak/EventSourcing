<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\UUID\UUID;

class BasketHasBeenCreated extends Event
{

    /**
     * BasketHasBeenCreatedEvent constructor.
     * @param UUID $eventId
     * @param \DateTime $dateTime
     * @param Basket $basket
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, Basket $basket)
    {
        parent::__construct($eventId, $dateTime);
        $this->setBasket($basket);
    }

    public function getEventFamilyName(): string
    {
        return 'Basket';
    }

}