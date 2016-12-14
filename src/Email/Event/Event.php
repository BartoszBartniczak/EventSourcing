<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;

use Shop\Email\Email;
use Shop\Event\Event as BasicEvent;
use Shop\EventAggregate\EventAggregate;


abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Email';

    /**
     * @var Email
     */
    protected $email;

    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    public function getEventAggregate(): EventAggregate
    {
        return $this->getEmail();
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


}