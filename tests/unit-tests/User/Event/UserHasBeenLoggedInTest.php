<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\EventTestCase;

class UserHasBeenLoggedInTest extends EventTestCase
{

    /**
     * @covers \Shop\User\Event\UserHasBeenLoggedIn::__construct
     */
    public function testGetters()
    {

        $event = new UserHasBeenLoggedIn(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@user.com'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame('user@user.com', $event->getUserEmail());

    }

}
