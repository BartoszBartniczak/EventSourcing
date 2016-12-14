<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command;


use Shop\Basket\Basket;
use Shop\Command\Command;
use Shop\UUID\UUID;

class RemoveProductFromTheBasket implements Command
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
     * RemoveProductFromTheBasket constructor.
     * @param Basket $basket
     * @param UUID $productId
     */
    public function __construct(Basket $basket, UUID $productId)
    {
        $this->basket = $basket;
        $this->productId = $productId;
    }

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

}