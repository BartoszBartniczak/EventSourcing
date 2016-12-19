<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;
use Shop\Event\Id;
use Shop\EventAggregate\EventAggregate;
use Shop\Product\Product;

class ProductHasBeenAddedToTheBasket extends Event
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var float
     */
    private $quantity;

    /**
     * ProductHasBeenAddedToTheBasket constructor.
     * @param Id $eventId
     * @param \DateTime $eventDateTime
     * @param Basket $basket
     * @param Product $product
     * @param float $quantity
     */
    public function __construct(Id $eventId, \DateTime $eventDateTime, Basket $basket, Product $product, float $quantity)
    {
        parent::__construct($eventId, $eventDateTime);
        $this->setBasket($basket);
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getEventAggregate(): EventAggregate
    {
        return $this->getBasket();
    }


}