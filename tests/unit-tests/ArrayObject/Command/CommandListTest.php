<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Command;


use Shop\ArrayObject\ArrayOfObjects;

class CommandListTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Command\CommandList::__construct
     */
    public function testConstructor()
    {
        $commandList = new CommandList();
        $this->assertInstanceOf(ArrayOfObjects::class, $commandList);
        $this->assertEquals(Command::class, $commandList->getClassName());
    }

}
