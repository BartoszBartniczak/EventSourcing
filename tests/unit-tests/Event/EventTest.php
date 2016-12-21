<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event;


use Shop\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \Shop\Event\Event::__construct
     * @covers \Shop\Event\Event::getEventId
     * @covers \Shop\Event\Event::getDateTime
     * @covers \Shop\Event\Event::getName
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
        /* @var $event \Shop\Event\Event */

        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('EventMock', $event->getName());
    }

}
