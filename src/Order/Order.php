<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order;


use Shop\Basket\Id as BasketId;
use Shop\Basket\Position\Position as BasketPosition;
use Shop\Basket\Position\PositionArray as BasketPositions;
use Shop\EventAggregate\EventAggregate;
use Shop\Order\Event\OrderHasBeenCreated;
use Shop\Order\Id as OrderId;
use Shop\Order\Position\Position;
use Shop\Order\Position\PositionArray;

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
     */
    public function __construct(Id $orderId, BasketId $basketId)
    {
        parent::__construct();
        $this->orderId = $orderId;
        $this->basketId = $basketId;
        $this->positions = new PositionArray();
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
     * @return PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }

    /**
     * @param BasketPositions $basketPositions
     */
    public function addPositionsFromBasket(BasketPositions $basketPositions)
    {
        foreach ($basketPositions as $basketPosition) {
            /* @var $basketPosition BasketPosition */
            $orderPosition = new Position($basketPosition->getProduct(), $basketPosition->getQuantity());
            $this->positions[] = $orderPosition;
        }
    }

    /**
     * @param OrderHasBeenCreated $orderHasBeenCreated
     */
    protected function handleOrderHasBeenCreated(OrderHasBeenCreated $orderHasBeenCreated)
    {
        $this->orderId = $orderHasBeenCreated->getOrderId();
        $this->basketId = $orderHasBeenCreated->getBasketId();
        $this->positions = $orderHasBeenCreated->getPositions();
    }

}