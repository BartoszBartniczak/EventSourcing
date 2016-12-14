<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\UUID\UUID;

class BasketHasBeenClosed extends Event
{

    /**
     * @var Basket
     */
    private $basket;

    public function __construct(UUID $eventId, \DateTime $dateTime, Basket $basket)
    {
        parent::__construct($eventId, $dateTime);
        $this->basket = $basket;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }
}