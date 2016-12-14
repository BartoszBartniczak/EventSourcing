<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command;


use Shop\Basket\Basket;
use Shop\Command\Command;

class CloseBasket implements Command
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * CloseBasket constructor.
     * @param Basket $basket
     */
    public function __construct(Basket $basket)
    {
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