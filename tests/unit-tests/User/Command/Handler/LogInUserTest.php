<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Password\HashGenerator;
use Shop\User\Command\LogInUser as LogInUserCommand;
use Shop\User\Repository\UserRepository;
use Shop\User\User;
use Shop\UUID\Generator;

class LogInUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleValidPassword()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash',
                'verifyUserPassword'
            ])
            ->getMock();
        $hashGenerator->method('hash')
            ->willReturn('passwordHash');
        $hashGenerator->method('verifyUserPassword')
            ->willReturn(true);
        /* @var $hashGenerator HashGenerator */

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $command = new LogInUserCommand(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );

        $logInUserHandler = new LogInUser($generator);
        $user = $logInUserHandler->handle($command);

        $this->assertEquals(1, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userHasBeenLoggedIn = $user->getUncommittedEvents()->shift();
        /* @var $userHasBeenLoggedIn \Shop\User\Event\UserHasBeenLoggedIn */
        $this->assertSame($userHasBeenLoggedIn->getDateTime(), $user->getLoginDates()->shift());
        $this->assertSame('email@user.com', $userHasBeenLoggedIn->getUserEmail());
    }

    /**
     * @covers \Shop\User\Command\Handler\LogInUser::handle
     */
    public function testHandleInvalidPassword()
    {

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash',
                'verifyUserPassword'
            ])
            ->getMock();
        $hashGenerator->method('hash')
            ->willReturn('passwordHash');
        $hashGenerator->method('verifyUserPassword')
            ->willReturn(false);
        /* @var $hashGenerator HashGenerator */

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->method('findUserByEmail')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $command = new LogInUserCommand(
            'email@user.com',
            'password',
            $hashGenerator,
            $userRepository
        );

        $logInUserHandler = new LogInUser($generator);
        $user = $logInUserHandler->handle($command);

        $this->assertEquals(0, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfLoggingIn = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfLoggingIn \Shop\User\Event\UnsuccessfulAttemptOfLoggingIn */
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfLoggingIn->getUserEmail());
        $this->assertEquals(1, $user->getUnsuccessfulAttemptsOfLoggingIn());
    }

}
