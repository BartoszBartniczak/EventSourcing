<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;


class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Event\Event::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidArgumentException());
    }

}
