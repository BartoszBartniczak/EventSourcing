<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;

use Shop\Event\Event as BasicEvent;
use Shop\User\User;
use Shop\UUID\UUID;


abstract class Event extends BasicEvent
{
    const FAMILY_NAME = 'User';

    /**
     * @var User
     */
    protected $userEmail;

    public function __construct(UUID $eventId, \DateTime $dateTime, string $userEmail)
    {
        parent::__construct($eventId, $dateTime);
        $this->userEmail = $userEmail;
    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }


}