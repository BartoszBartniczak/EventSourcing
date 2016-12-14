<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Event;


use Shop\User\User;
use Shop\UUID\UUID;

class UserHasBeenRegistered extends Event
{
    /**
     * @var string
     */
    private $passwordHash;

    /**
     * @var string
     */
    private $passwordSalt;

    /**
     * UserHasBeenRegistered constructor.
     * @param UUID $eventId
     * @param \DateTime $dateTime
     * @param User $userEmail
     * @param string $passwordHash
     * @param string $passwordSalt
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, string $userEmail, string $passwordHash, string $passwordSalt)
    {
        parent::__construct($eventId, $dateTime, $userEmail);
        $this->passwordHash = $passwordHash;
        $this->passwordSalt = $passwordSalt;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getPasswordSalt(): string
    {
        return $this->passwordSalt;
    }


}