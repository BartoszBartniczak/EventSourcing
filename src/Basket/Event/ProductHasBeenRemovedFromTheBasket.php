<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\Event\Id;
use Shop\Product\Id as ProductId;
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
     * @param ProductId $productId
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Basket $basket, ProductId $productId)
    {
        parent::__construct($eventId, $dateTime);

        $this->basket = $basket;
        $this->productId = $productId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

}