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

class QuantityOfTheProductHasBeenChanged extends Event
{
    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var UUID
     */
    private $productId;

    /**
     * @var float
     */
    private $quantity;

    /**
     * QuantityOfTheProductHasBeenChanged constructor.
     * @param Basket $basket
     * @param UUID $productId
     * @param float $quantity
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, Basket $basket, UUID $productId, float $quantity)
    {
        parent::__construct($eventId, $dateTime);

        $this->basket = $basket;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
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

    /**
     * @return UUID
     */
    public function getProductId(): UUID
    {
        return $this->productId;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }


}