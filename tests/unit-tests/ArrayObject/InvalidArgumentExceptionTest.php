<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\ArrayObject;

use BartoszBartniczak\ArrayObject\InvalidArgumentException as InvalidArgumentExceptionBasic;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects::__construct
     */
    public function testConstructor()
    {

        $this->assertInstanceOf(InvalidArgumentExceptionBasic::class, new InvalidArgumentException());

    }

}
