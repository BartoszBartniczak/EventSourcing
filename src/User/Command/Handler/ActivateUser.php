<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\EventAggregate\EventAggregate;
use Shop\User\Command\ActivateUser as ActivateUserCommand;
use Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount as UnsuccessfulAttemptOfActivatingUserAccountEvent;
use Shop\User\Event\UserAccountHasBeenActivated as UserAccountHasBeenActivatedEvent;
use Shop\User\Repository\UserRepository;
use Shop\User\User;

class ActivateUser extends CommandHandler
{
    /**
     * @var string
     */
    private $activationToken;

    /**
     * @var User
     */
    private $userEmail;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var bool;
     */
    private $isTokenValid;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var User
     */
    private $user;

    /**
     * @param Command|ActivateUserCommand $command
     * @return EventAggregate
     */
    public function handle(Command $command): EventAggregate
    {
        $this->userEmail = $command->getUserEmail();
        $this->activationToken = $command->getActivationToken();
        $this->userRepository = $command->getUserRepository();
        $this->user = $this->userRepository->findUserByEmail($this->userEmail);

        $this->validateToken();

        if ($this->isTokenValid) {
            $this->user->apply(new UserAccountHasBeenActivatedEvent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->userEmail,
                $this->activationToken
            ));
        } else {
            $this->user->apply(new UnsuccessfulAttemptOfActivatingUserAccountEvent(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $this->userEmail,
                    $this->activationToken,
                    $this->errorMessage
                )
            );
        }

        return $this->user;
    }

    private function validateToken()
    {


        if ($this->user->isActive() === false && $this->user->getActivationToken() === $this->activationToken) {
            $this->isTokenValid = true;
        } elseif ($this->user->isActive()) {
            $this->isTokenValid = false;
            $this->errorMessage = 'User has been already activated';
        } else {
            $this->isTokenValid = false;
            $this->errorMessage = 'Invalid activation token.';
        }
    }


}