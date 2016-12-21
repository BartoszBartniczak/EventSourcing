<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\ExceptionTestCase;

class InvalidArgumentExceptionTestCase extends ExceptionTestCase
{

    /**
     * @covers \Shop\User\Repository\InvalidArgumentException::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidArgumentException());
        $this->assertConstructorDoesNotRequiredAnyArguments(InvalidArgumentException::class);
        $this->assertConstructorUsesStandardArguments(InvalidArgumentException::class);
    }


}
