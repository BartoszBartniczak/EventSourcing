<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event;


use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Event::__construct
     * @covers \BartoszBartniczak\EventSourcing\Event\Event::getEventId
     * @covers \BartoszBartniczak\EventSourcing\Event\Event::getDateTime
     * @covers \BartoszBartniczak\EventSourcing\Event\Event::getName
     */
    public function testGetters()
    {

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime()
            ])
            ->setMockClassName('EventMock')
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('EventMock', $event->getName());
    }

}
