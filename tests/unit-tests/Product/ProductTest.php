<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product;


class ProductTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Product\Product::__construct
     * @covers \Shop\Product\Product::getId
     * @covers \Shop\Product\Product::getName
     */
    public function testGetters()
    {
        $id = new Id(uniqid());

        $product = new Product($id, 'Milk');
        $this->assertSame($id, $product->getId());
        $this->assertSame('Milk', $product->getName());
    }

}
