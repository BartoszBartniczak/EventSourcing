<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command;


use Shop\Basket\Basket;
use Shop\Command\Command;
use Shop\UUID\UUID;

class ChangeQuantityOfTheProduct implements Command
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
     * ChangeQuantityOfTheProduct constructor.
     * @param Basket $basket
     * @param UUID $productId
     * @param float $quantity
     */
    public function __construct(Basket $basket, UUID $productId, $quantity)
    {
        $this->basket = $basket;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return void
     */
    public function execute()
    {
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