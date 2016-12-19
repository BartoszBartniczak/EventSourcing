<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event;


class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Event\Event::__construct
     * @covers \Shop\Event\Event::getEventId
     * @covers \Shop\Event\Event::getDateTime
     * @covers \Shop\Event\Event::getName
     */
    public function testGetters()
    {
        $eventId = new Id(uniqid());
        $dateTime = new \DateTime();

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $eventId,
                $dateTime
            ])
            ->setMockClassName('EventMock')
            ->getMockForAbstractClass();
        /* @var $event \Shop\Event\Event */

        $this->assertSame($eventId, $event->getEventId());
        $this->assertSame($dateTime, $event->getDateTime());
        $this->assertEquals('EventMock', $event->getName());
    }

}
