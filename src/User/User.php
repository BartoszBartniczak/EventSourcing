<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User;


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
     * @var array
     */
    private $loginDates;

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
        $this->active = false;
        $this->passwordHash = $passwordHash;
        $this->passwordSalt = $passwordSalt;
        $this->loginDates = [];
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

    protected function handleUserHasBeenRegistered(UserHasBeenRegistered $event)
    {
        $this->email = $event->getUserEmail();
        $this->passwordHash = $event->getPasswordHash();
        $this->passwordSalt = $event->getPasswordSalt();
    }

    protected function handleActivationTokenHasBeenGenerated(ActivationTokenHasBeenGenerated $event)
    {
        $this->changeActivationToken($event->getActivationToken());
    }

    private function changeActivationToken($newToken)
    {
        $this->activationToken = $newToken;
    }


    protected function handleUserAccountHasBeenActivated(UserAccountHasBeenActivated $event)
    {
        $this->activate();
    }

    private function activate()
    {
        $this->active = true;
    }

    protected function handleUnsuccessfulAttemptOfActivatingUserAccount(UnsuccessfulAttemptOfActivatingUserAccount $event)
    {

    }

    protected function handleUserHasBeenLoggedIn(UserHasBeenLoggedIn $event)
    {
        $this->loginDates[] = $event->getDateTime()->format('Y-m-d H:i:s');
    }

    protected function handleUserHasBeenLoggedOut(UserHasBeenLoggedOut $event)
    {

    }

    private function deactivate()
    {
        $this->active = false;
    }


}