<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\ArrayObject;


use BartoszBartniczak\ArrayObject\ArrayOfObjects as ArrayOfObjectsBasic;

class ArrayOfObjectsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::__construct
     */
    public function testConstructor()
    {
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class);
        $this->assertInstanceOf(ArrayOfObjectsBasic::class, $arrayOfObjects);

        $arrayOfObjects = new ArrayOfObjects(\DateTime::class, [new \DateTime()]);
        $this->assertEquals(1, $arrayOfObjects->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::pop
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
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::shift
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
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::merge
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
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::merge
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::throwExceptionIfObjectIsNotInstanceOfTheClass
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
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::isNotEmpty
     */
    public function testIsNotEmpty()
    {
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class);
        $this->assertFalse($arrayOfObjects->isNotEmpty());

        $arrayOfObjects->append(new \DateTime());
        $this->assertTrue($arrayOfObjects->isNotEmpty());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::isEmpty
     */
    public function testIsEmpty()
    {
        $arrayOfObjects = new ArrayOfObjects(\DateTime::class);
        $this->assertTrue($arrayOfObjects->isEmpty());

        $arrayOfObjects->append(new \DateTime());
        $this->assertFalse($arrayOfObjects->isEmpty());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::filter
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
