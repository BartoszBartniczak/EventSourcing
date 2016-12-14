<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order;


use Shop\Basket\Id as BasketId;
use Shop\EventAggregate\EventAggregate;
use Shop\Order\Event\OrderHasBeenCreated;
use Shop\Order\Id as OrderId;

class Order extends EventAggregate
{

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var array
     */
    private $positions;

    /**
     * Order constructor.
     * @param Id $orderId
     * @param BasketId $basketId
     * @param array $positions
     */
    public function __construct(Id $orderId, BasketId $basketId, array $positions)
    {
        parent::__construct();
        $this->orderId = $orderId;
        $this->basketId = $basketId;
        $this->positions = $positions;
    }

    /**
     * @return Id
     */
    public function getOrderId(): Id
    {
        return $this->orderId;
    }

    /**
     * @return BasketId
     */
    public function getBasketId(): BasketId
    {
        return $this->basketId;
    }

    /**
     * @return array
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param OrderHasBeenCreated $orderHasBeenCreated
     */
    public function handleOrderHasBeenCreated(OrderHasBeenCreated $orderHasBeenCreated)
    {
        $this->orderId = $orderHasBeenCreated->getOrderId();
        $this->basketId = $orderHasBeenCreated->getBasketId();
        $this->positions = $orderHasBeenCreated->getPositions();
    }

}