<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\User\Repository\UserRepository;

class ActivateUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\ActivateUser::__construct
     * @covers \Shop\User\Command\ActivateUser::getUserEmail
     * @covers \Shop\User\Command\ActivateUser::getActivationToken
     * @covers \Shop\User\Command\ActivateUser::getUserRepository
     */
    public function testGetters()
    {

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->getMock();
        /* @var $userRepository UserRepository */

        $command = new ActivateUser(
            'email@user.com',
            'activationToken',
            $userRepository
        );

        $this->assertSame('email@user.com', $command->getUserEmail());
        $this->assertSame('activationToken', $command->getActivationToken());
        $this->assertSame($userRepository, $command->getUserRepository());
    }

}
