<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email;


use Shop\UUID\UUID;

class IdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Email\Id::__construct
     */
    public function testConstructor()
    {
        $id = new Id(uniqid());
        $this->assertInstanceOf(UUID::class, $id);
    }

}
