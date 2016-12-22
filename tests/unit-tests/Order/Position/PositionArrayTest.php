<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Position;


use Shop\ArrayObject\ArrayOfObjects;

class PositionArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Shop\Order\Position\PositionArray::__construct
     */
    public function testConstructor()
    {

        $positionArray = new PositionArray();
        $this->assertInstanceOf(ArrayOfObjects::class, $positionArray);
        $this->assertEquals(Position::class, $positionArray->getClassName());

        $positions = [];
        $positions[] = $this->getMockBuilder(Position::class)
            ->disableOriginalConstructor()
            ->getMock();

        $positionArray = new PositionArray($positions);
        $this->assertEquals(1, $positionArray->count());
    }
}
