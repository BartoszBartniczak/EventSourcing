<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\ArrayObject;


use BartoszBartniczak\ArrayObject\ArrayOfObjects as ArrayOfObjectsBasic;

class ArrayOfObjectsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::__construct
     */
    public function testConstructor()
    {
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class);
        $this->assertInstanceOf(ArrayOfObjectsBasic::class, $arrayOfObjects);

        $arrayOfObjects = new ArrayOfObjects(\DateTime::class, [new \DateTime()]);
        $this->assertEquals(1, $arrayOfObjects->count());
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::pop
     */
    public function testPop()
    {
        $first = new \DateTime();
        $second = new \DateTime();
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class, [$first, $second]);
        $this->assertSame($second, $arrayOfObjects->pop());
        $this->assertEquals(1, $arrayOfObjects->count());
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::shift
     */
    public function testShift()
    {
        $first = new \DateTime();
        $second = new \DateTime();
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class, [$first, $second]);
        $this->assertSame($first, $arrayOfObjects->shift());
        $this->assertEquals(1, $arrayOfObjects->count());
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::merge
     */
    public function testMerge()
    {
        $first = new \DateTime();
        $second = new \DateTime();
        $arrayOfObjectsFirst = new ArrayOfObjects(\DateTime::class, [$first]);
        $arrayOfObjectsSecond = new ArrayOfObjects(\DateTime::class, [$second]);
        $arrayOfObjectsFirst->merge($arrayOfObjectsSecond);
        $this->assertEquals(2, $arrayOfObjectsFirst->count());
        $this->assertSame($first, $arrayOfObjectsFirst->shift());
        $this->assertSame($second, $arrayOfObjectsFirst->shift());
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::merge
     * @covers \Shop\ArrayObject\ArrayOfObjects::throwExceptionIfObjectIsNotInstanceOfTheClass
     */
    public function testMergeThrowsInvalidArgumentExceptionIfTheObjectAreNotInstanceOfTheClass()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Instance of '\DateTime' expected. '\DateTimeZone' given.");

        $arrayOfObjectsFirst = new ArrayOfObjects(\DateTime::class, [new \DateTime()]);
        $arrayOfObjectsSecond = new ArrayOfObjects(\DateTimeZone::class, [new \DateTimeZone('Europe/Warsaw')]);
        $arrayOfObjectsFirst->merge($arrayOfObjectsSecond);
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::isNotEmpty
     */
    public function testIsNotEmpty()
    {
        $arrayOfObjectsFirst = new ArrayOfObjects(\DateTime::class);
        $this->assertFalse($arrayOfObjectsFirst->isNotEmpty());

        $arrayOfObjectsFirst->append(new \DateTime());
        $this->assertTrue($arrayOfObjectsFirst->isNotEmpty());
    }

    /**
     * @covers \Shop\ArrayObject\ArrayOfObjects::isNotEmpty
     */
    public function testFilter()
    {
        $arrayOfObjectsFirst = new ArrayOfObjects(\DateTime::class, [new \DateTime('2016-12-16'), new \DateTime('2016-12-15')]);
        $filteredDates = $arrayOfObjectsFirst->filter(function (\DateTime $dateTime) {
            return $dateTime >= new \DateTime('2016-12-16');
        });
        $this->assertEquals(new ArrayOfObjects(\DateTime::class, [new \DateTime('2016-12-16')]), $filteredDates);
    }
}
