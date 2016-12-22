<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product;


use Shop\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Product\Id::__construct
     */
    public function testConstructor()
    {

        $id = new Id(uniqid());
        $this->assertInstanceOf(UUID::class, $id);

    }

}
