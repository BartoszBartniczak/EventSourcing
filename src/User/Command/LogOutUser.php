<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\Command\Command;
use Shop\User\User;

class LogOutUser implements Command
{

    /**
     * @var User
     */
    private $user;

    /**
     * LogOutUser constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}