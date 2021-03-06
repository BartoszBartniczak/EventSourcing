<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


use BartoszBartniczak\EventSourcing\Event\Event;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Serializer;

class JMSJsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer::__construct
     */
    public function testConstructor()
    {
        $jmsSerializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->getMock();
        /* @var $jmsSerializerMock Serializer */

        $propertyNamingStrategyInterface = $this->getMockBuilder(PropertyNamingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $propertyNamingStrategyInterface PropertyNamingStrategyInterface */

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock, $propertyNamingStrategyInterface);
        $this->assertInstanceOf(\BartoszBartniczak\EventSourcing\Event\Serializer\Serializer::class, $jmsJsonSerializer);
    }


    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer::serialize
     */
    public function testSerialize()
    {
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $event Event */

        $jmsSerializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->getMock();
        $jmsSerializerMock->method('serialize')
            ->with($event, 'json')
            ->willReturn('{}');
        /* @var $jmsSerializerMock Serializer */

        $propertyNamingStrategyInterface = $this->getMockBuilder(PropertyNamingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $propertyNamingStrategyInterface PropertyNamingStrategyInterface */

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock, $propertyNamingStrategyInterface);
        $this->assertEquals('{}', $jmsJsonSerializer->serialize($event));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer::deserialize
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer::tryToExtractClassName
     */
    public function testDeserialize()
    {
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jmsSerializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->getMock();
        $jmsSerializerMock->method('deserialize')
            ->with('{"name": "Event"}', 'Event', 'json')
            ->willReturn($event);
        /* @var $jmsSerializerMock Serializer */

        $propertyNamingStrategyInterface = $this->getMockBuilder(PropertyNamingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $propertyNamingStrategyInterface PropertyNamingStrategyInterface */

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock, $propertyNamingStrategyInterface);
        $this->assertSame($event, $jmsJsonSerializer->deserialize('{"name": "Event"}'));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer::tryToExtractClassName
     */
    public function testTryToExtractClassNameThrowsExceptionIfNameIsNotDefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot extract class name of the event');

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jmsSerializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'serialize',
                'deserialize'
            ])
            ->getMock();
        /* @var $jmsSerializerMock Serializer */

        $propertyNamingStrategyInterface = $this->getMockBuilder(PropertyNamingStrategyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $propertyNamingStrategyInterface PropertyNamingStrategyInterface */

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock, $propertyNamingStrategyInterface);
        $this->assertSame($event, $jmsJsonSerializer->deserialize('{}'));
    }

}
