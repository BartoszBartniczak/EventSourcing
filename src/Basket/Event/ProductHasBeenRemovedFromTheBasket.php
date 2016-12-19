<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\Event\Id;
use Shop\UUID\UUID;

class ProductHasBeenRemovedFromTheBasket extends Event
{

    /**
     * @var UUID
     */
    private $productId;

    /**
     * ProductHasBeenRemovedFromTheBasket constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param Basket $basket
     * @param UUID $productId
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Basket $basket, UUID $productId)
    {
        parent::__construct($eventId, $dateTime);

        $this->basket = $basket;
        $this->productId = $productId;
    }

    /**
     * @return UUID
     */
    public function getProductId(): UUID
    {
        return $this->productId;
    }

}