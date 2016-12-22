<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email;

use Shop\Email\Event\EmailHasBeenSent;
use Shop\Email\Event\EmailHasNotBeenSent;


class EmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Email\Email::__construct
     * @covers \Shop\Email\Email::getId()
     * @covers \Shop\Email\Email::isSent()
     * @covers \Shop\Email\Email::getUnsuccessfulAttemptsOfSending()
     */
    public function testConstructor()
    {

        $id = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $id Id */

        $email = new Email($id);
        $this->assertSame($id, $email->getId());
        $this->assertFalse($email->isSent());
        $this->assertEquals(0, $email->getUnsuccessfulAttemptsOfSending());
    }

    /**
     * @covers \Shop\Email\Email::handleEmailHasBeenSent
     * @covers \Shop\Email\Email::markAsSent()
     */
    public function testHandleEmailHasBeenSent()
    {

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $email = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $email->method('findHandleMethod')
            ->willReturn('handleEmailHasBeenSent');
        /* @var $email Email */

        $emailHasBeenSent = $this->getMockBuilder(EmailHasBeenSent::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailHasBeenSent EmailHasBeenSent */

        $email->apply($emailHasBeenSent);
        /* @var $emailHasBeenSent EmailHasBeenSent */
        $this->assertTrue($email->isSent());
    }

    /**
     * @covers \Shop\Email\Email::handleEmailHasNotBeenSent
     * @covers \Shop\Email\Email::getUnsuccessfulAttemptsOfSending()
     */
    public function testHandleEmailHasNotBeenSent()
    {

        $emailId = $this->getMockBuilder(Id::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailId Id */

        $emailHasNotBeenSent = $this->getMockBuilder(EmailHasNotBeenSent::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $emailHasNotBeenSent EmailHasNotBeenSent */

        $email = $this->getMockBuilder(Email::class)
            ->setConstructorArgs([
                $emailId
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();

        $email->method('findHandleMethod')
            ->willReturn('handleEmailHasNotBeenSent');
        /* @var $email Email */
        $email->apply($emailHasNotBeenSent);

        $this->assertEquals(1, $email->getUnsuccessfulAttemptsOfSending());
    }

}
