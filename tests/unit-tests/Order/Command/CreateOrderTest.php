<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Command;


use Shop\Basket\Basket;
use Shop\Email\Email;
use Shop\Email\Sender\Service;
use Shop\UUID\Generator;

class CreateOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Order\Command\CreateOrder::__construct
     * @covers \Shop\Order\Command\CreateOrder::getUuidGenerator()
     * @covers \Shop\Order\Command\CreateOrder::getBasket()
     * @covers \Shop\Order\Command\CreateOrder::getEmailSenderService()
     * @covers \Shop\Order\Command\CreateOrder::getEmail
     */
    public function testGetters()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $service = $this->getMockBuilder(Service::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $service Service */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $createOrder = new CreateOrder($generator, $basket, $service, $email);
        $this->assertSame($generator, $createOrder->getUuidGenerator());
        $this->assertSame($basket, $createOrder->getBasket());
        $this->assertSame($service, $createOrder->getEmailSenderService());
        $this->assertSame($email, $createOrder->getEmail());
    }

}
