<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\EventAggregate\EventAggregate;
use Shop\User\Event\UserHasBeenLoggedIn;

class LogInUser extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \Shop\User\Command\LogInUser */

        $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());
        $loginStatus = $command->getHashGenerator()->verifyUserPassword($command->getPassword(), $user);

        if ($loginStatus === true) {
            $user->apply(new UserHasBeenLoggedIn(
                $this->uuidGenerator->generate(),
                new \DateTime(),
                $command->getUserEmail()
            ));
        } else {
            /* @todo Unsuccessful Attempt Of Log In Event */
            throw new \InvalidArgumentException();
        }

        return $user;
    }

}