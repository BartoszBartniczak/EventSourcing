<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;


use JMS\Serializer\Serializer;
use Shop\Event\Event;

class JMSJsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Event\Serializer\JMSJsonSerializer::__construct
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

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock);
        $this->assertInstanceOf(\Shop\Event\Serializer\Serializer::class, $jmsJsonSerializer);
    }


    /**
     * @covers \Shop\Event\Serializer\JMSJsonSerializer::serialize
     */
    public function testSerialize()
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
        $jmsSerializerMock->method('serialize')
            ->with($event, 'json')
            ->willReturn('{}');
        /* @var $jmsSerializerMock Serializer */

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock);
        $this->assertEquals('{}', $jmsJsonSerializer->serialize($event));
    }

    /**
     * @covers \Shop\Event\Serializer\JMSJsonSerializer::deserialize
     * @covers \Shop\Event\Serializer\JMSJsonSerializer::tryToExtractClassName
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

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock);
        $this->assertSame($event, $jmsJsonSerializer->deserialize('{"name": "Event"}'));
    }

    /**
     * @covers \Shop\Event\Serializer\JMSJsonSerializer::tryToExtractClassName
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

        $jmsJsonSerializer = new JMSJsonSerializer($jmsSerializerMock);
        $this->assertSame($event, $jmsJsonSerializer->deserialize('{}'));
    }
}
