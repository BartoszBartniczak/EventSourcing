<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\CQRS\Command\CommandList;
use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Event\EventStream;
use BartoszBartniczak\EventSourcing\Event\Id;
use BartoszBartniczak\EventSourcing\UUID\Generator;

/**
 * Class CommandHandlerMock
 * @package Shop\Basket\Command\Handler
 */
class CommandHandlerMock extends CommandHandler
{

    public function generateEventId(): Id
    {
        return parent::generateEventId();
    }

    public function generateDateTime(): \DateTime
    {
        return parent::generateDateTime();
    }

    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
    }

    public function addNextCommand(Command $command)
    {
        parent::addNextCommand($command);
    }

    public function addAdditionalEvent(Event $event)
    {
        parent::addAdditionalEvent($event);
    }

}


class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::__construct
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::getAdditionalEvents
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::getNextCommands
     */
    public function testConstructor()
    {

        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $uuidGenerator
            ])
            ->getMockForAbstractClass();
        /* @var $commandHandler CommandHandler */

        $this->assertInstanceOf(CommandList::class, $commandHandler->getNextCommands());
        $this->assertEquals(0, $commandHandler->getNextCommands()->count());
        $this->assertInstanceOf(EventStream::class, $commandHandler->getAdditionalEvents());
        $this->assertEquals(0, $commandHandler->getAdditionalEvents()->count());

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::generateEventId
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::generateDateTime
     */
    public function testGenerators()
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $commandHandlerMock = new CommandHandlerMock($uuidGenerator);

        $this->assertInstanceOf(Id::class, $commandHandlerMock->generateEventId());
        $this->assertNotSame($commandHandlerMock->generateEventId(), $commandHandlerMock->generateEventId());
        $this->assertInstanceOf(\DateTime::class, $commandHandlerMock->generateDateTime());
        $this->assertNotSame($commandHandlerMock->generateDateTime(), $commandHandlerMock->generateDateTime());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::addNextCommand
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::getNextCommands
     */
    public function testAddNextCommand()
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $command = $this->getMockBuilder(Command::class)
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandHandlerMock = new CommandHandlerMock($uuidGenerator);
        $commandHandlerMock->addNextCommand($command);
        $commandHandlerMock->addNextCommand($command);
        $this->assertEquals(2, $commandHandlerMock->getNextCommands()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::addAdditionalEvent
     * @covers \BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler::getAdditionalEvents
     */
    public function testAddAdditionalEvent()
    {
        $uuidGenerator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $uuidGenerator Generator */

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event Event */

        $commandHandlerMock = new CommandHandlerMock($uuidGenerator);
        $commandHandlerMock->addAdditionalEvent($event);
    }


}
