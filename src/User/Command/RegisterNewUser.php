<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\Command\Command;
use Shop\Email\Sender\Service as EmailSenderService;
use Shop\Generator\ActivationTokenGenerator;
use Shop\Password\HashGenerator;
use Shop\Password\SaltGenerator;
use Shop\UUID\Generator as UUIDGenerator;

class RegisterNewUser implements Command
{

    /**
     * @var EmailSenderService
     */
    private $emailSenderService;

    /**
     * @var $userEmail
     */
    private $userEmail;

    /**
     * @var string
     */
    private $userPassword;

    /**
     * @var string
     */
    private $activationToken;

    /**
     * @var ActivationTokenGenerator;
     */
    private $activationTokenGenerator;

    /**
     * @var UUIDGenerator
     */
    private $uuidGenerator;

    /**
     * @var SaltGenerator
     */
    private $saltGenerator;

    /**
     * @var HashGenerator
     */
    private $hashGenerator;

    /**
     * RegisterNewUser constructor.
     * @param string $userEmail
     * @param string $userPassword
     * @param EmailSenderService $emailSenderService
     * @param ActivationTokenGenerator $activationTokenGenerator
     * @param UUIDGenerator $generator
     * @param SaltGenerator $saltGenerator
     * @param HashGenerator $hashGenerator
     */
    public function __construct(string $userEmail, string $userPassword, EmailSenderService $emailSenderService, ActivationTokenGenerator $activationTokenGenerator, UUIDGenerator $generator, SaltGenerator $saltGenerator, HashGenerator $hashGenerator)
    {
        $this->emailSenderService = $emailSenderService;
        $this->userEmail = $userEmail;
        $this->activationTokenGenerator = $activationTokenGenerator;
        $this->uuidGenerator = $generator;
        $this->saltGenerator = $saltGenerator;
        $this->userPassword = $userPassword;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    /**
     * @return EmailSenderService
     */
    public function getEmailSenderService(): EmailSenderService
    {
        return $this->emailSenderService;
    }

    /**
     * @return SaltGenerator
     */
    public function getSaltGenerator(): SaltGenerator
    {
        return $this->saltGenerator;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @return ActivationTokenGenerator
     */
    public function getActivationTokenGenerator(): ActivationTokenGenerator
    {
        return $this->activationTokenGenerator;
    }

    /**
     * @return UUIDGenerator
     */
    public function getUuidGenerator(): UUIDGenerator
    {
        return $this->uuidGenerator;
    }

    /**
     * @return HashGenerator
     */
    public function getHashGenerator(): HashGenerator
    {
        return $this->hashGenerator;
    }

    /**
     * @return string
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }


}