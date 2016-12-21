<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\EventTestCase;

class ActivationTokenHasBeenGeneratedTest extends EventTestCase
{

    /**
     * @covers \Shop\User\Event\ActivationTokenHasBeenGenerated::__construct
     * @covers \Shop\User\Event\ActivationTokenHasBeenGenerated::getActivationToken
     */
    public function testGetters()
    {

        $event = new ActivationTokenHasBeenGenerated(
            $this->generateEventId(),
            $this->generateDateTime(),
            'user@email.com',
            'activationToken'
        );

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSameEventIdAsGenerated($event);
        $this->assertSameDateTimeAsGenerated($event);
        $this->assertEquals('user@email.com', $event->getUserEmail());
        $this->assertEquals('activationToken', $event->getActivationToken());
    }

}
