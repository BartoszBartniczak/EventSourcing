<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Repository;


use Shop\ExceptionTestCase;

class CannotFindUserExceptionTest extends ExceptionTestCase
{

    /**
     * @covers \Shop\User\Repository\CannotFindUserException::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(InvalidArgumentException::class, new CannotFindUserException());
        $this->assertConstructorDoesNotRequiredAnyArguments(CannotFindUserException::class);
        $this->assertConstructorUsesStandardArguments(CannotFindUserException::class);
    }

}
