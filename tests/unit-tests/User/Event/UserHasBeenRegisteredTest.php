<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\EventTestCase;

class UserHasBeenRegisteredTest extends EventTestCase
{

    /**
     * @covers \Shop\User\Event\UserHasBeenRegistered::__construct
     * @covers \Shop\User\Event\UserHasBeenRegistered::getPasswordHash
     * @covers \Shop\User\Event\UserHasBeenRegistered::getPasswordSalt
     */
    public function testGetters()
    {
        $event = new UserHasBeenRegistered(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@email.com',
            'password',
            'salt'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('user@email.com', $event->getUserEmail());
        $this->assertEquals('password', $event->getPasswordHash());
        $this->assertEquals('salt', $event->getPasswordSalt());
    }

}
