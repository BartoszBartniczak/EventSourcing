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
    public function handle(Command $command): EventAggregate
    {
        $salt = $command->getSaltGenerator()->generate();
        $this->user = new User($command->getUserEmail(), $command->getHashGenerator()->hash($command->getUserPassword(), $salt), $salt);
        $activationToken = $command->getActivationTokenGenerator()->generate();
        $email = new Email($command->getUuidGenerator()->generate());
        $this->user->apply(
            new UserHasBeenRegistered(
                $this->uuidGenerator->generate(),
                new \DateTime(),
                $this->user->getEmail(),
                $this->user->getPasswordHash(),
                $this->user->getPasswordSalt()
            )
        );

        $this->user->apply(new ActivationTokenHasBeenGenerated(
            $this->uuidGenerator->generate(),
            new \DateTime(),
            $this->user->getEmail(),
            $activationToken
        ));

        $this->addNextCommand(new SendEmailCommand($command->getEmailSenderService(), new Email($this->uuidGenerator->generate())));

        return $this->user;
    }

}