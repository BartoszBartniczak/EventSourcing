<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;


use Shop\Email\Email;
use Shop\Event\Event as BasicEvent;
use Shop\EventTestCase;

class EventTest extends EventTestCase
{

    /**
     * @covers \Shop\Email\Event\Event::__construct
     * @covers \Shop\Email\Event\Event::getEventFamilyName()
     * @covers \Shop\Email\Event\Event::getEmail()
     */
    public function testGetters()
    {

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $event = $this->getMockBuilder(Event::class)
            ->setConstructorArgs([
                $this->generateEventId(),
                $this->generateDateTime(),
                $email
            ])
            ->setMethods(null)
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertEquals(Event::FAMILY_NAME, $event->getEventFamilyName());
        $this->assertSame($email, $event->getEmail());
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
    }

}
