<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\User\Event\UserHasBeenLoggedOut as UserHasBeenLoggedOutEvent;

class LogOutUser extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
        /* @var $command \Shop\User\Command\LogOutUser */
        $command->getUser()->apply(
            new UserHasBeenLoggedOutEvent(
                $this->uuidGenerator->generate(),
                new \DateTime(),
                $command->getUser()->getEmail()
            )
        );

        return $command->getUser();
    }

}