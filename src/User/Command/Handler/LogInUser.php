<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\User\Event\UnsuccessfulAttemptOfLoggingIn;
use Shop\User\Event\UserHasBeenLoggedIn;
use Shop\User\User;

class LogInUser extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): User
    {
        /* @var $command \Shop\User\Command\LogInUser */

        $user = $command->getUserRepository()->findUserByEmail($command->getUserEmail());
        $loginStatus = $command->getHashGenerator()->verifyUserPassword($command->getPassword(), $user);

        if ($loginStatus === true) {
            $user->apply(new UserHasBeenLoggedIn(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getUserEmail()
            ));
        } else {
            $user->apply(
                new UnsuccessfulAttemptOfLoggingIn(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getUserEmail()
                )
            );
        }

        return $user;
    }

}