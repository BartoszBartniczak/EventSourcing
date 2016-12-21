<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\EventTestCase;

class UnsuccessfulAttemptOfActivatingUserAccountTest extends EventTestCase
{

    /**
     * @covers \Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount::__construct
     * @covers \Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount::getActivationToken
     * @covers \Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount::getMessage
     */
    public function testGetters()
    {

        $event = new UnsuccessfulAttemptOfActivatingUserAccount(
            $this->generateEventId(),
            $this->generateDateTime(),
            'email@user.com',
            'wrongToken',
            'The token is wrong.'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertSame('email@user.com', $event->getUserEmail());
        $this->assertSame('wrongToken', $event->getActivationToken());
        $this->assertSame('The token is wrong.', $event->getMessage());
    }

}
