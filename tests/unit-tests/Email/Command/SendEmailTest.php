<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Command;


use Shop\Command\Command;
use Shop\Email\Email;
use Shop\Email\Sender\Service;

class SendEmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Email\Command\SendEmail::__construct
     * @covers \Shop\Email\Command\SendEmail::getEmail()
     * @covers \Shop\Email\Command\SendEmail::getEmailSenderService()
     */
    public function testGetters()
    {
        $service = $this->getMockBuilder(Service::class)
            ->getMockForAbstractClass();
        /* @var $service Service */

        $email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $email Email */

        $sendEmail = new SendEmail($service, $email);
        $this->assertInstanceOf(Command::class, $sendEmail);
        $this->assertSame($service, $sendEmail->getEmailSenderService());
        $this->assertSame($email, $sendEmail->getEmail());
    }

}
