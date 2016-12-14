<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\User\User;

interface UserRepository
{

    public function findUserByEmail(string $email): User;

}