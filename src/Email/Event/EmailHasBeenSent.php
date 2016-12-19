<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;

use Shop\Email\Email;
use Shop\Event\Id;

class EmailHasBeenSent extends Event
{
    public function __construct(Id $eventId, \DateTime $dateTime, Email $email)
    {
        parent::__construct($eventId, $dateTime);
        $this->email = $email;
    }

}