<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\Event\Event as BasicEvent;
use Shop\EventTestCase;

class EventTest extends EventTestCase
{
    /**
     * @covers \Shop\User\Event\Event::__construct
     * @covers \Shop\User\Event\Event::getEventFamilyName
     * @covers \Shop\User\Event\Event::getUserEmail
     */
    public function testGetters()
    {


        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs(
                [
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    'user@company.com'
                ]
            )
            ->getMockForAbstractClass();
        /* @var $event \Shop\User\Event\Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
        $this->assertEquals('user@company.com', $event->getUserEmail());
    }

}
