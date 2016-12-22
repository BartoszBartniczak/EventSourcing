<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\EventTestCase;

class UnsuccessfulAttemptOfLoggingInTest extends EventTestCase
{

    /**
     * @covers \Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount::__construct
     */
    public function testGetters()
    {

        $event = new UnsuccessfulAttemptOfLoggingIn(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@email.com'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame('user@email.com', $event->getUserEmail());

    }

}
