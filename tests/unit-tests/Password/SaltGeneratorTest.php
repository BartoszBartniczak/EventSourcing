<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Password;


class SaltGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Password\SaltGenerator::generate
     */
    public function testGenerate()
    {
        $saltGenerator = new SaltGenerator();
        $this->assertNotEmpty($saltGenerator->generate());
        $this->assertNotEquals($saltGenerator->generate(), $saltGenerator->generate());
        $this->assertTrue(strlen($saltGenerator->generate()) >= 22);
    }

}
