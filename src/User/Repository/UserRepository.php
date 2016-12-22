<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\User\User;

interface UserRepository
{
    /**
     * @param string $email
     * @return User
     * @throws CannotFindUserException
     */
    public function findUserByEmail(string $email): User;

}