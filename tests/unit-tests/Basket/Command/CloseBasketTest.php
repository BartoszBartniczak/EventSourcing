<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Command\Command;

class CloseBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket::getBasket
     */
    public function testGetters()
    {
        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $closeBasket = new CloseBasket($basket);

        $this->assertInstanceOf(Command::class, $closeBasket);
        $this->assertSame($basket, $closeBasket->getBasket());

    }

}
