<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Repository;


use Shop\Basket\Basket;
use Shop\Basket\Id;
use Shop\Event\Dispatcher\Dispatcher;
use Shop\Event\Repository\InMemoryEventRepository;
use Shop\UUID\UUID;

class InMemoryRepository extends InMemoryEventRepository implements BasketRepository
{

    /**
     * @param UUID $basketId
     * @return Basket
     */
    public function findBasket(Id $basketId): Basket
    {
        $eventStream = $this->find('Basket', $basketId->toNative());

        $basket = new Basket($basketId, '');
        $basket->applyEventStream($eventStream);
        $basket->commit();
        return $basket;
    }

    public function findLastBasketByUserEmail(string $userEmail): Basket
    {
        $eventStream = $this->find('Basket', '');

        $basket = new Basket(new Id(''), '');
        $basket->applyEventStream($eventStream);
        $basket->commit();

        return $basket;
    }


}