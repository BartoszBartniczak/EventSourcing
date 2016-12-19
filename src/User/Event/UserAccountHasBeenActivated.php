<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\Event\Id;

class UserAccountHasBeenActivated extends Event
{
    /**
     * @var string
     */
    protected $activationToken;

    /**
     * @inheritDoc
     */
    public function __construct(Id $eventId, \DateTime $dateTime, string $userEmail, string $activationToken)
    {
        parent::__construct($eventId, $dateTime, $userEmail);
        $this->activationToken = $activationToken;
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

}