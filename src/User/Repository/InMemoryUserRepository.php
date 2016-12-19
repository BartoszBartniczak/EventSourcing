<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\Event\Repository\InMemoryEventRepository;
use Shop\User\User;

class InMemoryUserRepository implements UserRepository
{

    /**
     * @var InMemoryEventRepository
     */
    private $inMemoryEventRepository;

    /**
     * InMemoryUserRepository constructor.
     * @param InMemoryEventRepository $inMemoryEventRepository
     */
    public function __construct(InMemoryEventRepository $inMemoryEventRepository)
    {
        $this->inMemoryEventRepository = $inMemoryEventRepository;
    }

    public function findUserByEmail(string $email): User
    {
        $eventStream = $this->inMemoryEventRepository->find('User', ['user_email' => function ($eventData) use ($email) {

            $userEvent = $this->inMemoryEventRepository->getEventSerializer()->deserialize($eventData);
            /* @var $userEvent \Shop\User\Event\Event */


            if ($userEvent->getUserEmail() === $email) {
                return true;
            } else {
                return false;
            }


        }]);

        $user = new User($email, '', ''); //user mock
        $user->applyEventStream($eventStream);
        $user->commit();
        return $user;
    }

}