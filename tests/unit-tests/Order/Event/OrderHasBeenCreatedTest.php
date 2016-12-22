<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Event;


use Shop\Basket\Id as BasketId;
use Shop\EventTestCase;
use Shop\Order\Id;
use Shop\Order\Position\PositionArray;

class OrderHasBeenCreatedTest extends EventTestCase
{

    /**
     * @covers \Shop\Order\Event\OrderHasBeenCreated::__construct
     * @covers \Shop\Order\Event\OrderHasBeenCreated::getOrderId()
     * @covers \Shop\Order\Event\OrderHasBeenCreated::getBasketId()
     * @covers \Shop\Order\Event\OrderHasBeenCreated::getPositions()
     */
    public function testGetters()
    {

        $orderId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $orderId Id */

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basketId BasketId */

        $positions = $this->getMockBuilder(PositionArray::class)
            ->getMock();
        /* @var $positions PositionArray */

        $orderHasBeenCreated = new OrderHasBeenCreated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $orderId,
            $basketId,
            $positions
        );

        $this->assertInstanceOf(Event::class, $orderHasBeenCreated);
        $this->assertSameEventIdAsGenerated($orderHasBeenCreated);
        $this->assertSameDateTimeAsGenerated($orderHasBeenCreated);
        $this->assertSame($orderId, $orderHasBeenCreated->getOrderId());
        $this->assertSame($basketId, $orderHasBeenCreated->getBasketId());
        $this->assertSame($positions, $orderHasBeenCreated->getPositions());
    }

}
