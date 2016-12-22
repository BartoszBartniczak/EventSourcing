<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Sender;


use Shop\ExceptionTestCase;

class CannotSendEmailExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\Email\Sender\CannotSendEmailException::__construct
     */
    public function testConstructor()
    {

        $cannotSendEmailException = new CannotSendEmailException();
        $this->assertInstanceOf(Exception::class, $cannotSendEmailException);
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotSendEmailException::class);
        $this->assertConstructorUsesStandardArguments(CannotSendEmailException::class);
    }

}
