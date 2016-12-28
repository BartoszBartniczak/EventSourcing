<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\ArrayObject;


class ArrayObjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayObject::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(\ArrayObject::class, new ArrayObject());

        $arrayObject = new ArrayObject([1, 2, 3]);
        $this->assertEquals(3, $arrayObject->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayObject::shift
     */
    public function testShift()
    {
        $arrayObject = new ArrayObject([1, 2, 3]);
        $this->assertEquals(1, $arrayObject->shift());
        $this->assertEquals(2, $arrayObject->shift());
        $this->assertEquals(3, $arrayObject->shift());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayObject::filter
     */
    public function testFilter()
    {
        $arrayObject = new ArrayObject([1, 2, 3]);
        $filteredData = $arrayObject->filter(function ($element) {
            return $element >= 2;
        });
        $this->assertEquals(2, $filteredData->count());
        $this->assertNotSame($arrayObject, $filteredData);
    }

}
