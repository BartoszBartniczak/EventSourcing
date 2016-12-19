<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


class HashInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Password\HashInfo::__construct
     * @covers \Shop\Password\HashInfo::getAlgorithm
     * @covers \Shop\Password\HashInfo::getAlgorithmName
     * @covers \Shop\Password\HashInfo::getCost
     */
    public function testGetters()
    {
        $hashInfo = new HashInfo(1, 'bcrypt', 10);
        $this->assertSame(1, $hashInfo->getAlgorithm());
        $this->assertEquals('bcrypt', $hashInfo->getAlgorithmName());
        $this->assertSame(10, $hashInfo->getCost());
    }

}
