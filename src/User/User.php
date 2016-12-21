<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User;


use Shop\ArrayObject\ArrayObject;
use Shop\EventAggregate\EventAggregate;
use Shop\User\Event\ActivationTokenHasBeenGenerated;
use Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use Shop\User\Event\UserAccountHasBeenActivated;
use Shop\User\Event\UserHasBeenLoggedIn;
use Shop\User\Event\UserHasBeenLoggedOut;
use Shop\User\Event\UserHasBeenRegistered;

class User extends EventAggregate
{

    /**
     * The email is the ID of the User
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $activationToken;

    /**
     * @var string
     */
    private $passwordHash;

    /**
     * @var string
     */
    private $passwordSalt;

    /**
     * @var ArrayObject
     */
    private $loginDates;

    /**
     * @var int
     */
    private $unsuccessfulAttemptsOfActivatingUserAccount;

    /**
     * User constructor.
     * @param string $email
     * @param string $passwordHash
     * @param string $passwordSalt
     */
    public function __construct(string $email, string $passwordHash, string $passwordSalt)
    {
        parent::__construct();

        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->passwordSalt = $passwordSalt;

        $this->active = false;
        $this->loginDates = new ArrayObject();
        $this->unsuccessfulAttemptsOfActivatingUserAccount = 0;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getActivationToken()
    {
        return $this->activationToken;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return mixed
     */
    public function getPasswordSalt(): string
    {
        return $this->passwordSalt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getLoginDates(): ArrayObject
    {
        return $this->loginDates;
    }

    /**
     * @return int
     */
    public function getUnsuccessfulAttemptsOfActivatingUserAccount(): int
    {
        return $this->unsuccessfulAttemptsOfActivatingUserAccount;
    }

    /**
     * @param UserHasBeenRegistered $event
     */
    protected function handleUserHasBeenRegistered(UserHasBeenRegistered $event)
    {
        $this->email = $event->getUserEmail();
        $this->passwordHash = $event->getPasswordHash();
        $this->passwordSalt = $event->getPasswordSalt();
    }

    /**
     * @param ActivationTokenHasBeenGenerated $event
     */
    protected function handleActivationTokenHasBeenGenerated(ActivationTokenHasBeenGenerated $event)
    {
        $this->changeActivationToken($event->getActivationToken());
    }

    /**
     * @param string $newToken
     */
    private function changeActivationToken(string $newToken)
    {
        $this->activationToken = $newToken;
    }

    /**
     * @param UserAccountHasBeenActivated $event
     */
    protected function handleUserAccountHasBeenActivated(UserAccountHasBeenActivated $event)
    {
        $this->activate();
    }

    /**
     * @return void
     */
    private function activate()
    {
        $this->active = true;
    }

    /**
     * @param UnsuccessfulAttemptOfActivatingUserAccount $event
     */
    protected function handleUnsuccessfulAttemptOfActivatingUserAccount(UnsuccessfulAttemptOfActivatingUserAccount $event)
    {
        $this->unsuccessfulAttemptsOfActivatingUserAccount++;
    }

    /**
     * @param UserHasBeenLoggedIn $event
     */
    protected function handleUserHasBeenLoggedIn(UserHasBeenLoggedIn $event)
    {
        $this->loginDates[] = $event->getDateTime();
    }

    protected function handleUserHasBeenLoggedOut(UserHasBeenLoggedOut $event)
    {

    }

}