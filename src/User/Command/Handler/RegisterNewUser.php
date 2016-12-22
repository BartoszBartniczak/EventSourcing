<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\Email\Command\SendEmail as SendEmailCommand;
use Shop\Email\Email;
use Shop\EventAggregate\EventAggregate;
use Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use Shop\User\Event\ActivationTokenHasBeenGenerated;
use Shop\User\Event\UserHasBeenRegistered;
use Shop\User\User;

class RegisterNewUser extends CommandHandler
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param Command|RegisterNewUserCommand $command
     * @return EventAggregate;
     */
    public function handle(Command $command): User
    {
        $salt = $command->getSaltGenerator()->generate();
        $passwordHash = $command->getHashGenerator()->hash($command->getUserPassword(), $salt);
        $this->user = new User($command->getUserEmail(), $passwordHash, $salt);
        $activationToken = $command->getActivationTokenGenerator()->generate();

        $this->user->apply(
            new UserHasBeenRegistered(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->user->getEmail(),
                $this->user->getPasswordHash(),
                $this->user->getPasswordSalt()
            )
        );

        $this->user->apply(new ActivationTokenHasBeenGenerated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $this->user->getEmail(),
            $activationToken
        ));

        $this->addNextCommand(new SendEmailCommand($command->getEmailSenderService(), new Email($this->uuidGenerator->generate())));

        return $this->user;
    }

}