<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


class SaltGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Password\SaltGenerator::generate
     */
    public function testGenerate()
    {
        $saltGenerator = new SaltGenerator();
        $this->assertNotEmpty($saltGenerator->generate());
        $this->assertNotEquals($saltGenerator->generate(), $saltGenerator->generate());
        $this->assertTrue(strlen($saltGenerator->generate()) >= 22);
    }

}
