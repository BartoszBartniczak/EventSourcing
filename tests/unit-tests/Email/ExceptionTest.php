<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email;


use Shop\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\Email\Exception::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(\Exception::class, new Exception());
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
        $this->assertConstructorUsesStandardArguments(Exception::class);
    }

}
