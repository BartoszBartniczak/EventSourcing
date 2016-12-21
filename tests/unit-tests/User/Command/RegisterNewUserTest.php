<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\Email\Sender\Service;
use Shop\Generator\ActivationTokenGenerator;
use Shop\Password\HashGenerator;
use Shop\Password\SaltGenerator;
use Shop\UUID\Generator;

class RegisterNewUserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\Command\RegisterNewUser::__construct
     * @covers \Shop\User\Command\RegisterNewUser::getUserEmail
     * @covers \Shop\User\Command\RegisterNewUser::getUserPassword
     * @covers \Shop\User\Command\RegisterNewUser::getEmailSenderService
     * @covers \Shop\User\Command\RegisterNewUser::getActivationTokenGenerator
     * @covers \Shop\User\Command\RegisterNewUser::getUuidGenerator
     * @covers \Shop\User\Command\RegisterNewUser::getSaltGenerator
     * @covers \Shop\User\Command\RegisterNewUser::getHashGenerator
     */
    public function testGetters()
    {

        $emailSenderService = $this->getMockBuilder(Service::class)
            ->getMock();
        /* @var $emailSenderService Service */

        $activationTokenGenerator = $this->getMockBuilder(ActivationTokenGenerator::class)
            ->getMock();
        /* @var $activationTokenGenerator ActivationTokenGenerator */

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $saltGenerator = new SaltGenerator();
        $hashGenerator = new HashGenerator();

        $command = new RegisterNewUser(
            'user@email.com',
            'password',
            $emailSenderService,
            $activationTokenGenerator,
            $uuidGenerator,
            $saltGenerator,
            $hashGenerator
        );

        $this->assertSame('user@email.com', $command->getUserEmail());
        $this->assertSame('password', $command->getUserPassword());
        $this->assertSame($emailSenderService, $command->getEmailSenderService());
        $this->assertSame($activationTokenGenerator, $command->getActivationTokenGenerator());
        $this->assertSame($uuidGenerator, $command->getUuidGenerator());
        $this->assertSame($saltGenerator, $command->getSaltGenerator());
        $this->assertSame($hashGenerator, $command->getHashGenerator());

    }

}
