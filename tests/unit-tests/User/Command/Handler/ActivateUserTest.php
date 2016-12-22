<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;

use Shop\User\Command\ActivateUser as ActivateUserCommand;
use Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use Shop\User\Event\UserAccountHasBeenActivated;
use Shop\User\Repository\UserRepository;
use Shop\User\User;
use Shop\UUID\Generator;

class ActivateUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\Handler\ActivateUser::handle
     * @covers \Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleValidActivationToken()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken'
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        /* @var $userMock User */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $command = new ActivateUserCommand(
            'email@user.com',
            'activationToken',
            $userRepository
        );

        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertTrue($user->isActive());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userAccountHasBeenActivatedEvent = $user->getUncommittedEvents()->shift();
        $this->assertInstanceOf(UserAccountHasBeenActivated::class, $userAccountHasBeenActivatedEvent);
        /* @var $userAccountHasBeenActivatedEvent UserAccountHasBeenActivated */
        $this->assertSame('email@user.com', $userAccountHasBeenActivatedEvent->getUserEmail());
        $this->assertSame('activationToken', $userAccountHasBeenActivatedEvent->getActivationToken());
    }

    /**
     * @covers \Shop\User\Command\Handler\ActivateUser::handle
     * @covers \Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleUserHasBeenAlreadyActivated()
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken',
                'isActive'
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        $userMock->method('isActive')
            ->willReturn(true);
        /* @var $userMock User */

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $command = new ActivateUserCommand(
            'email@user.com',
            'activationToken',
            $userRepository
        );

        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfActivatingUserAccount = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */
        $this->assertInstanceOf(UnsuccessfulAttemptOfActivatingUserAccount::class, $unsuccessfulAttemptOfActivatingUserAccount);
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfActivatingUserAccount->getUserEmail());
        $this->assertSame('activationToken', $unsuccessfulAttemptOfActivatingUserAccount->getActivationToken());
        $this->assertSame('User has been already activated.', $unsuccessfulAttemptOfActivatingUserAccount->getMessage());
    }

    /**
     * @covers \Shop\User\Command\Handler\ActivateUser::handle
     * @covers \Shop\User\Command\Handler\ActivateUser::tokenValidation
     */
    public function testHandleInvalidActivationToken()
    {

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $uuidGenerator Generator */

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setMethods([
                'findUserByEmail'
            ])
            ->getMock();

        $userMock = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'getActivationToken',
            ])
            ->getMock();
        $userMock->method('getActivationToken')
            ->willReturn('activationToken');
        /* @var $userMock User */

        $userRepository->expects($this->once())
            ->method('findUserByEmail')
            ->with('email@user.com')
            ->willReturn($userMock);
        /* @var $userRepository UserRepository */

        $command = new ActivateUserCommand(
            'email@user.com',
            'wrongActivationToken',
            $userRepository
        );


        $activateUserHandler = new ActivateUser($uuidGenerator);
        $user = $activateUserHandler->handle($command);
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $unsuccessfulAttemptOfActivatingUserAccount = $user->getUncommittedEvents()->shift();
        /* @var $unsuccessfulAttemptOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */
        $this->assertInstanceOf(UnsuccessfulAttemptOfActivatingUserAccount::class, $unsuccessfulAttemptOfActivatingUserAccount);
        $this->assertSame('email@user.com', $unsuccessfulAttemptOfActivatingUserAccount->getUserEmail());
        $this->assertSame('wrongActivationToken', $unsuccessfulAttemptOfActivatingUserAccount->getActivationToken());
        $this->assertSame('Invalid activation token.', $unsuccessfulAttemptOfActivatingUserAccount->getMessage());
    }

}
