<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\UUID;


class RamseyGeneratorAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter::generate
     */
    public function testGenerate()
    {

        $ramseyGenerator = new RamseyGeneratorAdapter();
        $this->assertNotEmpty($ramseyGenerator->generate());
        $this->assertNotEquals($ramseyGenerator->generate(), $ramseyGenerator->generate());
    }

}
