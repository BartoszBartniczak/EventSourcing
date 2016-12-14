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

class ProductHasBeenRemovedFromTheBasket extends Event
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var UUID
     */
    private $productUuid;

    /**
     * ProductHasBeenRemovedFromTheBasket constructor.
     * @param Basket $basket
     * @param UUID $productUuid
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, Basket $basket, UUID $productUuid)
    {
        parent::__construct($eventId, $dateTime);

        $this->basket = $basket;
        $this->productUuid = $productUuid;
    }

    public function getEventFamilyName(): string
    {
        return 'Basket';
    }

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
    public function getProductUuid(): UUID
    {
        return $this->productUuid;
    }

}