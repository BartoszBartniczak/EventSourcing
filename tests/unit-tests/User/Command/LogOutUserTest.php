<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\User\Repository\UserRepository;

class LogOutUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\LogOutUser::__construct
     * @covers \Shop\User\Command\LogOutUser::getUserEmail
     * @covers \Shop\User\Command\LogOutUser::getUserRepository
     */
    public function testGetters()
    {

        $userEmail = 'user@email.com';

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userRepository UserRepository */

        $logOutUserCommand = new LogOutUser($userEmail, $userRepository);
        $this->assertSame('user@email.com', $logOutUserCommand->getUserEmail());
        $this->assertSame($userRepository, $logOutUserCommand->getUserRepository());
    }

}
