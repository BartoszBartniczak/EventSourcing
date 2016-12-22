<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\Password\HashGenerator;
use Shop\User\Repository\UserRepository;

class LogInUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\LogInUser::__construct
     * @covers \Shop\User\Command\LogInUser::getPassword
     * @covers \Shop\User\Command\LogInUser::getUserEmail
     * @covers \Shop\User\Command\LogInUser::getHashGenerator
     * @covers \Shop\User\Command\LogInUser::getUserRepository
     */
    public function testGetters()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->getMock();
        /* @var $hashGenerator HashGenerator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userRepository UserRepository */

        $command = new LogInUser(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );
        $this->assertSame('email@user.com', $command->getUserEmail());
        $this->assertSame('password', $command->getPassword());
        $this->assertSame($hashGenerator, $command->getHashGenerator());
        $this->assertSame($userRepository, $command->getUserRepository());
    }

}
