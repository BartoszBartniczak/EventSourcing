<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository;


use Shop\ExceptionTestCase;

class CannotFindProductExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\Product\Repository\CannotFindProductException::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Exception::class, new CannotFindProductException());
        $this->assertConstructorUsesStandardArguments(CannotFindProductException::class);
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindProductException::class);
    }

}
