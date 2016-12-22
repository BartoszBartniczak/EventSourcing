<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Position;


use Shop\Product\Product;

class PositionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Order\Position\Position::__construct
     * @covers \Shop\Order\Position\Position::getProduct()
     * @covers \Shop\Order\Position\Position::getQuantity()
     */
    public function testGetters()
    {

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $position = new Position($product, 12.3);
        $this->assertSame($product, $position->getProduct());
        $this->assertSame(12.3, $position->getQuantity());

    }

}
