<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command\Handler;


use Shop\Email\Command\SendEmail;
use Shop\Email\Sender\Service;
use Shop\Generator\ActivationTokenGenerator;
use Shop\Password\HashGenerator;
use Shop\Password\SaltGenerator;
use Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use Shop\User\Event\ActivationTokenHasBeenGenerated;
use Shop\User\Event\UserHasBeenRegistered;
use Shop\User\User;
use Shop\UUID\Generator;

class RegisterNewUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\Handler\RegisterNewUser::handle
     */
    public function testHandle()
    {

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $emailSenderService = $this->getMockBuilder(Service::class)
            ->getMock();
        /* @var $emailSenderService Service */

        $activationTokenGenerator = $this->getMockBuilder(ActivationTokenGenerator::class)
            ->setMethods([
                'generate'
            ])
            ->getMock();
        $activationTokenGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('activationToken');
        /* @var $activationTokenGenerator ActivationTokenGenerator */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $saltGenerator = $this->getMockBuilder(SaltGenerator::class)
            ->setMethods([
                'generate'
            ])
            ->getMock();
        $saltGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('passwordSalt');
        /* @var $saltGenerator SaltGenerator */

        $hashGenerator = $this->getMockBuilder(HashGenerator::class)
            ->setMethods([
                'hash'
            ])
            ->getMock();
        $hashGenerator->expects($this->once())
            ->method('hash')
            ->with('password', 'passwordSalt')
            ->willReturn('passwordHash');
        /* @var $hashGenerator HashGenerator */

        $command = new RegisterNewUserCommand(
            'user@email.com',
            'password',
            $emailSenderService,
            $activationTokenGenerator,
            $uuidGenerator,
            $saltGenerator,
            $hashGenerator
        );

        $registerNewUser = new RegisterNewUser($uuidGenerator);
        $user = $registerNewUser->handle($command);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('user@email.com', $user->getEmail());
        $this->assertSame('passwordHash', $user->getPasswordHash());
        $this->assertSame('passwordSalt', $user->getPasswordSalt());
        $this->assertSame('activationToken', $user->getActivationToken());
        $this->assertInstanceOf(SendEmail::class, $registerNewUser->getNextCommands()->shift());
        $this->assertEquals(0, $user->getCommittedEvents()->count());
        $userHasBeenRegistered = $user->getUncommittedEvents()->shift();
        /* @var $userHasBeenRegistered UserHasBeenRegistered */
        $this->assertInstanceOf(UserHasBeenRegistered::class, $userHasBeenRegistered);
        $this->assertSame('user@email.com', $userHasBeenRegistered->getUserEmail());
        $this->assertSame('passwordHash', $userHasBeenRegistered->getPasswordHash());
        $this->assertSame('passwordSalt', $userHasBeenRegistered->getPasswordSalt());
        $activationTokenHasBeenGenerated = $user->getUncommittedEvents()->shift();
        /* @var $activationTokenHasBeenGenerated ActivationTokenHasBeenGenerated */
        $this->assertInstanceOf(ActivationTokenHasBeenGenerated::class, $activationTokenHasBeenGenerated);
        $this->assertSame('activationToken', $activationTokenHasBeenGenerated->getActivationToken());
        $this->assertSame('user@email.com', $activationTokenHasBeenGenerated->getUserEmail());
    }

}
