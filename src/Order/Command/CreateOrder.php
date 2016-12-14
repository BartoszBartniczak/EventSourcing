<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Command;


use Shop\Basket\Basket;
use Shop\Command\Command;
use Shop\Email\Email;
use Shop\Email\Sender\Service as EmailSenderService;
use Shop\UUID\Generator;

class CreateOrder implements Command
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var Generator
     */
    private $uuidGenerator;
    /**
     * @var EmailSenderService
     */
    private $emailSenderService;
    /**
     * @var Email
     */
    private $email;

    /**
     * CreateOrder constructor.
     * @param Generator $uuidGenerator
     * @param Basket $basket
     * @param EmailSenderService $service
     * @param Email $email
     */
    public function __construct(Generator $uuidGenerator, Basket $basket, EmailSenderService $service, Email $email)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->basket = $basket;
        $this->emailSenderService = $service;
        $this->email = $email;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @return Generator
     */
    public function getUuidGenerator(): Generator
    {
        return $this->uuidGenerator;
    }

    /**
     * @return EmailSenderService
     */
    public function getEmailSenderService(): EmailSenderService
    {
        return $this->emailSenderService;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


}