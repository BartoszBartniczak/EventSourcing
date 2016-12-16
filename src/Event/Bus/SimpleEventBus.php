<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Bus;


use Shop\Email\Event\EmailHasNotBeenSent;
use Shop\Event\Event;
use Shop\EventAggregate\EventStream;

class SimpleEventBus implements EventBus
{

    public function emmit(EventStream $eventStream)
    {
        foreach ($eventStream as $event) {
            $this->handle($event);
        }
    }

    private function handle(Event $event)
    {
        try {
            $method = $this->findHandleMethod($event);
            $this->$method($event);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            //do nothing
        }

    }

    /**
     * @param Event $event
     * @return string
     * @throws \InvalidArgumentException
     */
    private function findHandleMethod(Event $event): string
    {

        $methods = [
            EmailHasNotBeenSent::class => 'handleEmailHasNotBeenSent'
        ];

        $className = get_class($event);
        if (isset($methods[$className])) {
            return $methods[$className];
        } else {
            throw new \InvalidArgumentException();
        }

    }

    private function handleEmailHasNotBeenSent(EmailHasNotBeenSent $emailHasNotBeenSent)
    {
        //in real application you would like to try to resend email
    }


}