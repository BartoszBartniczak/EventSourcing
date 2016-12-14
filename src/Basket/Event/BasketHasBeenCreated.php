<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\Event\Event;
use Shop\EventAggregate\EventAggregate;
use Shop\UUID\UUID;

class BasketHasBeenCreated extends Event
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * BasketHasBeenCreatedEvent constructor.
     * @param Basket $basket
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, Basket $basket)
    {
        parent::__construct($eventId, $dateTime);
        $this->basket = $basket;
    }

    public function getEventFamilyName(): string
    {
        return 'Basket';
    }

    /**
     * @return EventAggregate
     */
    public function getEventAggregate(): EventAggregate
    {
        return $this->getBasket();
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

}