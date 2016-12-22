<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\User\Event\UserHasBeenLoggedOut as UserHasBeenLoggedOutEvent;
use Shop\User\Repository\CannotFindUserException;
use Shop\User\User;

class LogOutUser extends CommandHandler
{
    /**
     * @inheritDoc
     * @throws CannotFindUserException
     */
    public function handle(Command $command): User
    {
        /* @var $command \Shop\User\Command\LogOutUser */

        $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());

        $user->apply(
            new UserHasBeenLoggedOutEvent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getUserEmail()
            )
        );

        return $user;
    }

}