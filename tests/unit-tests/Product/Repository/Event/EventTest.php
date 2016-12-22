<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Event;


use Shop\Event\Event as BasicEvent;

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Product\Repository\Event\Event::getEventFamilyName
     */
    public function testGetEventFamilyName()
    {

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMockForAbstractClass();
        /* @var $event Event */

        $this->assertInstanceOf(BasicEvent::class, $event);
        $this->assertSame(Event::FAMILY_NAME, $event->getEventFamilyName());
    }

}
