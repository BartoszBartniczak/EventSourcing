<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Command\Handler;

use Shop\Email\Command\SendEmail as SendEmailCommand;
use Shop\Email\Email;
use Shop\Email\Event\EmailHasBeenSent;
use Shop\Email\Event\EmailHasNotBeenSent;
use Shop\Email\Id;
use Shop\Email\Sender\CannotSendEmailException;
use Shop\Email\Sender\Service;
use Shop\UUID\Generator;


class SendEmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Email\Command\Handler\SendEmail::handle
     * @covers \Shop\Email\Command\Handler\SendEmail::isSent()
     */
    public function testHandleWithoutErrors()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $service = $this->getMockBuilder(Service::class)
            ->getMockForAbstractClass();
        /* @var $service Service */

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $emailMock = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $emailMock Email */

        $sendEmailCommand = new SendEmailCommand($service, $emailMock);

        $sendEmail = new SendEmail($generator);
        $email = $sendEmail->handle($sendEmailCommand);
        $this->assertSame($email, $emailMock);
        $this->assertTrue($email->isSent());
        $this->assertEquals(0, $email->getCommittedEvents()->count());
        $this->assertEquals(1, $email->getUncommittedEvents()->count());
        $emailHasBeenSent = $email->getUncommittedEvents()->shift();
        $this->assertInstanceOf(EmailHasBeenSent::class, $emailHasBeenSent);
        /* @var $emailHasBeenSent EmailHasBeenSent */
        $this->assertSame($email, $emailHasBeenSent->getEmail());
    }

    /**
     * @covers \Shop\Email\Command\Handler\SendEmail::handle
     * @covers \Shop\Email\Command\Handler\SendEmail::isSent()
     */
    public function testHandleSenderException()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $generator Generator */

        $service = $this->getMockBuilder(Service::class)
            ->setMethods([
                'send'
            ])
            ->getMockForAbstractClass();
        $service->method('send')
            ->willThrowException(new CannotSendEmailException("Exception Message"));
        /* @var $service Service */

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $emailMock = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods(null)
            ->getMock();
        /* @var $emailMock Email */

        $sendEmailCommand = new SendEmailCommand($service, $emailMock);

        $sendEmail = new SendEmail($generator);
        $email = $sendEmail->handle($sendEmailCommand);
        $this->assertSame($email, $emailMock);
        $this->assertFalse($email->isSent());
        $this->assertEquals(0, $email->getCommittedEvents()->count());
        $this->assertEquals(1, $email->getUncommittedEvents()->count());
        $emailHasNotBeenSent = $email->getUncommittedEvents()->shift();
        $this->assertInstanceOf(EmailHasNotBeenSent::class, $emailHasNotBeenSent);
        /* @var $emailHasNotBeenSent EmailHasNotBeenSent */
        $this->assertSame($email, $emailHasNotBeenSent->getEmail());
        $this->assertSame("Exception Message", $emailHasNotBeenSent->getErrorMessage());
    }

}
