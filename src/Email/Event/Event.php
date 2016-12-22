<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;

use Shop\Email\Email;
use Shop\Event\Event as BasicEvent;
use Shop\Event\Id;


abstract class Event extends BasicEvent
{

    const FAMILY_NAME = 'Email';

    /**
     * @var Email
     */
    protected $email;

    /**
     * Event constructor.
     * @param Id $eventId
     * @param \DateTime $dateTime
     * @param Email $email
     */
    public function __construct(Id $eventId, \DateTime $dateTime, Email $email)
    {
        parent::__construct($eventId, $dateTime);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


}