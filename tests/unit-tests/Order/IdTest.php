<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order;


use Shop\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Order\Id::__construct
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(UUID::class, new Id(uniqid()));
    }

}
