<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


use BartoszBartniczak\EventSourcing\Event\Event;

class FakeSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer::__construct
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer::serialize
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\FakeSerializer::deserialize
     */
    public function testSerialization()
    {

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */

        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $fakeSerializer = new FakeSerializer();
        $this->assertInstanceOf(Serializer::class, $fakeSerializer);

        $serializedEvent1 = $fakeSerializer->serialize($event1);
        $serializedEvent2 = $fakeSerializer->serialize($event2);

        $deserializedEvent1 = $fakeSerializer->deserialize($serializedEvent1);
        $deserializedEvent2 = $fakeSerializer->deserialize($serializedEvent2);

        $this->assertSame($event1, $deserializedEvent1);
        $this->assertSame($event2, $deserializedEvent2);
    }

}
