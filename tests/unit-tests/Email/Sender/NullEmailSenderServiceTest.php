<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Sender;


use Shop\Email\Email;

class NullEmailSenderServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Email\Sender\NullEmailSenderService::send
     * @covers \Shop\Email\Sender\NullEmailSenderService::__construct
     */
    public function testSendDoesNotThrowExceptionIfSwitchIsOff()
    {
        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */
        $nullEmailSenderService = new NullEmailSenderService();
        $nullEmailSenderService->send($email);
        $this->assertInstanceOf(Service::class, $nullEmailSenderService);
    }

    /**
     * @covers \Shop\Email\Sender\NullEmailSenderService::send
     * @covers \Shop\Email\Sender\NullEmailSenderService::__construct
     */
    public function testSendThrowsExceptionIfSwitchIsOff()
    {
        $this->expectException(CannotSendEmailException::class);
        $this->expectExceptionMessage('You are using NullEmailSenderService!');

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */
        $nullEmailSenderService = new NullEmailSenderService(true);
        $nullEmailSenderService->send($email);
    }

}
