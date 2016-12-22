<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;


use Shop\Email\Email;
use Shop\EventTestCase;

class EmailHasBeenSentTest extends EventTestCase
{

    /**
     * @covers \Shop\Email\Event\EmailHasBeenSent::__construct
     */
    public function testConstructor()
    {

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $emailHasBeenSent = new EmailHasBeenSent(
            $this->generateEventId(),
            $this->generateDateTime(),
            $email
        );

        $this->assertInstanceOf(Event::class, $emailHasBeenSent);
        $this->assertSameEventIdAsGenerated($emailHasBeenSent);
        $this->assertSameDateTimeAsGenerated($emailHasBeenSent);
        $this->assertSame($email, $emailHasBeenSent->getEmail());

    }

}
