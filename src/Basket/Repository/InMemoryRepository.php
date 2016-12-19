<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Repository;


use Shop\Basket\Basket;
use Shop\Basket\Event\BasketHasBeenCreated;
use Shop\Basket\Id;
use Shop\Event\Dispatcher\Dispatcher;
use Shop\Event\Repository\InMemoryEventRepository;

class InMemoryRepository implements BasketRepository
{

    /**
     * @var InMemoryEventRepository
     */
    private $inMemoryEventRepository;

    /**
     * InMemoryRepository constructor.
     * @param InMemoryEventRepository $inMemoryEventRepository
     */
    public function __construct(InMemoryEventRepository $inMemoryEventRepository)
    {
        $this->inMemoryEventRepository = $inMemoryEventRepository;
    }

    public function findLastBasketByUserEmail(string $userEmail): Basket
    {

        $propertyName = $this->inMemoryEventRepository->getEventSerializer()->getPropertyKey('name');
        $eventStream = $this->inMemoryEventRepository->find('Basket', ['lastBasket' => function ($serializedEvent) use ($propertyName, $userEmail) {

            $event = $this->inMemoryEventRepository->getEventSerializer()->deserialize($serializedEvent);
            /* @var $event \Shop\Basket\Event\Event */

            if ($event->getBasket()->getOwnerEmail() !== $userEmail) {
                return false;
            }

            if ($event->getName() !== BasketHasBeenCreated::class) {
                return false;
            }

            return true;

        }]);

        $basket = new Basket(new Id(''), '');
        $basket->applyEventStream($eventStream);
        $basket->commit();

        return $this->findBasket($basket->getId());
    }

    /**
     * @param Id $basketId
     * @return Basket
     */
    public function findBasket(Id $basketId): Basket
    {
        $eventStream = $this->inMemoryEventRepository->find('Basket', ['basketId' => function ($serializedEvent) use ($basketId) {
            $event = $this->inMemoryEventRepository->getEventSerializer()->deserialize($serializedEvent);
            /* @var $event \Shop\Basket\Event\Event */

            if ($event->getBasket()->getId()->toNative() !== $basketId->toNative()) {
                return false;
            }

            return true;
        }]);

        $basket = new Basket($basketId, '');
        $basket->applyEventStream($eventStream);
        $basket->commit();
        return $basket;
    }


}