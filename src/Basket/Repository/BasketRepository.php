<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Repository;


use Shop\Basket\Basket;
use Shop\Basket\Id;

interface BasketRepository
{
    public function findBasket(Id $basketId): Basket;

    public function findLastBasketByUserEmail(string $userEmail): Basket;

}