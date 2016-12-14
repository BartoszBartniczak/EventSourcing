<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Event;


use Shop\Email\Email;
use Shop\UUID\UUID;

class EmailHasNotBeenSent extends Event
{
    /**
     * @var string
     */
    protected $errorMessage;

    public function __construct(UUID $eventId, \DateTime $dateTime, Email $email, string $errorMessage)
    {
        parent::__construct($eventId, $dateTime);
        $this->email = $email;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}