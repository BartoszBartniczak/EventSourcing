<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Event;

use Shop\Basket\Id as BasketId;
use Shop\Event\Id;
use Shop\Order\Id as OrderId;


class OrderHasBeenCreated extends Event
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
     * OrderHasBeenCreated constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param OrderId $orderId
     * @param BasketId $basketId
     * @param array $positions
     */
    public function __construct(Id $eventId, \DateTime $dateTime, OrderId $orderId, BasketId $basketId, array $positions)
    {
        parent::__construct($eventId, $dateTime);
        $this->orderId = $orderId;
        $this->basketId = $basketId;
        $this->positions = $positions;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
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


}