<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository;


use Shop\ExceptionTestCase;

class ExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\Product\Repository\Exception::__construct
     */
    public function testConstructor()
    {

        $exception = new Exception();
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertConstructorDoesNotRequiredAnyArguments(Exception::class);
        $this->assertConstructorUsesStandardArguments(Exception::class);
    }

}
