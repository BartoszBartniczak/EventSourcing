<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\EventAggregate;


class CannotHandleTheEventExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $this->assertInstanceOf(Exception::class, new CannotHandleTheEventException());
    }

}
