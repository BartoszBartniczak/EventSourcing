<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Sender;


use Shop\Email\Exception as EmailException;
use Shop\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\Email\Sender\Exception::__construct
     */
    public function testConstructor()
    {

        $exception = new Exception();
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertNotInstanceOf(EmailException::class, $exception);
        $this->assertConstructorUsesStandardArguments(Exception::class);
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
    }

}
