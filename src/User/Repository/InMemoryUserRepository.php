<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\Event\Repository\InMemoryEventRepository;
use Shop\User\User;

class InMemoryUserRepository extends InMemoryEventRepository implements UserRepository
{

    public function findUserByEmail(string $email): User
    {
        $eventStream = $this->find('User', $email);
        $user = new User($email, '', ''); //user mock
        $user->applyEventStream($eventStream);
        $user->commit();
        return $user;
    }

}