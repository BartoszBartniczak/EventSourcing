<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Bus;


use BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Shop\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Command\CommandList;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\Exception as HandlerException;
use BartoszBartniczak\EventSourcing\Shop\Event\Bus\EventBus;
use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\EventRepository;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\TestCase;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;

class EventBusMock implements EventBus
{

    private $eventsExpected;

    /**
     * EventBusMock constructor.
     */
    public function __construct()
    {
        $this->eventsExpected = new ArrayObject();
    }


    public function emmit(EventStream $eventStream)
    {
        TestCase::assertEquals(2, $eventStream->count());
        foreach ($this->eventsExpected as $expectedEvent) {
            TestCase::assertSame($expectedEvent, $eventStream->shift());
        }
    }

    public function addEventToExpect(Event $event)
    {
        $this->eventsExpected[] = $event;
    }

}


class CommandBusTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::handle
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::findHandler
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::registerHandler
     */
    public function testHandle()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->getMockForAbstractClass();
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->getMockForAbstractClass();
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::findHandler
     */
    public function testFindHandlerThrowsCannotFindHandlerException()
    {
        $this->expectException(CannotFindHandlerException::class);
        $this->expectExceptionMessage("Cannot find handler for command: 'CommandMock'.");

        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->getMockForAbstractClass();
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */
        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->handle($command);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::saveOutput
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::getOutput
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::getOutputForCommand
     */
    public function testOutputFromOneCommand()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->getMockForAbstractClass();
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->getMockForAbstractClass();
        /* @var $eventAggregate EventAggregate */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'handle'
            ])
            ->getMockForAbstractClass();
        $commandHandler->method('handle')
            ->willReturn($eventAggregate);
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);

        $this->assertSame($eventAggregate, $commandBus->getOutput()['CommandMock']);
        $this->assertSame($eventAggregate, $commandBus->getOutputForCommand($command));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::saveOutput
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::getOutput
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::getOutputForCommand
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::handle
     */
    public function testOutputFromMultipleCommands()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->getMockForAbstractClass();
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */

        $eventAggregate2 = $this->getMockBuilder(EventAggregate::class)
            ->getMockForAbstractClass();
        /* @var $eventAggregate2 EventAggregate */

        $eventAggregate1 = $this->getMockBuilder(EventAggregate::class)
            ->getMockForAbstractClass();
        /* @var $eventAggregate1 EventAggregate */

        $command2 = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock2')
            ->getMockForAbstractClass();
        /* @var $command2 Command */

        $commandList = new CommandList();
        $commandList->append($command2);

        $commandHandler2 = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'handle',
            ])
            ->getMockForAbstractClass();
        $commandHandler2->method('handle')
            ->willReturn($eventAggregate2);
        /* @var $commandHandler2 CommandHandler */

        $commandHandler1 = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'handle',
                'getNextCommands'
            ])
            ->getMockForAbstractClass();
        $commandHandler1->method('handle')
            ->willReturn($eventAggregate1);
        $commandHandler1->method('getNextCommands')
            ->willReturn($commandList);
        /* @var $commandHandler1 CommandHandler */

        $command1 = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command1 Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler1);
        $commandBus->registerHandler('CommandMock2', $commandHandler2);
        $commandBus->handle($command1);

        $this->assertSame($eventAggregate1, $commandBus->getOutputForCommand($command1));
        $this->assertSame($eventAggregate2, $commandBus->getOutputForCommand($command2));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::handle()
     */
    public function testEventAggregateIsSavedInRepository()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */

        $eventStream = $this->getMockBuilder(EventStream::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $eventStream EventStream */

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->getMock();
        /* @var $eventAggregate EventAggregate */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods([
                'saveEventAggregate'
            ])
            ->getMockForAbstractClass();
        $eventRepository->expects($this->once())
            ->method('saveEventAggregate')
            ->with($eventAggregate);
        /* @var $eventRepository EventRepository */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'handle'
            ])
            ->getMockForAbstractClass();
        $commandHandler->method('handle')
            ->willReturn($eventAggregate);
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::handle()
     */
    public function testAdditionalEventsAreSavedInRepository()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $additionalEvents = $this->getMockBuilder(EventStream::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $additionalEvents EventStream */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods([
                'saveEventStream'
            ])
            ->getMockForAbstractClass();
        $eventRepository->expects($this->once())
            ->method('saveEventStream')
            ->with($additionalEvents);
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->getMockForAbstractClass();
        /* @var $eventBus EventBus */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'getAdditionalEvents'
            ])
            ->getMockForAbstractClass();
        $commandHandler->method('getAdditionalEvents')
            ->willReturn($additionalEvents);
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Command\Bus\CommandBus::handle()
     */
    public function testAllEventsAreEmitted()
    {
        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->getMockForAbstractClass();
        /* @var $eventRepository EventRepository */

        $event1 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event1 Event */

        $uncommittedEvents = new EventStream();
        $uncommittedEvents[] = $event1;

        $eventAggregate = $this->getMockBuilder(EventAggregate::class)
            ->setMethods([
                'getUncommittedEvents'
            ])
            ->getMockForAbstractClass();
        $eventAggregate->method('getUncommittedEvents')
            ->willReturn($uncommittedEvents);
        /* @var $eventAggregate EventAggregate */

        $event2 = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event2 Event */

        $additionalEvents = new EventStream();
        $additionalEvents[] = $event2;

        $eventBusMock = new EventBusMock();
        $eventBusMock->addEventToExpect($event1);
        $eventBusMock->addEventToExpect($event2);

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'handle',
                'getAdditionalEvents'
            ])
            ->getMockForAbstractClass();
        $commandHandler->method('handle')
            ->willReturn($eventAggregate);
        $commandHandler->method('getAdditionalEvents')
            ->willReturn($additionalEvents);
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBusMock);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);
    }


    public function testHandleError()
    {
        $this->expectException(CannotHandleTheCommandException::class);
        $this->expectExceptionMessage("Command 'CommandMock' cannot be handled.");

        $generator = $this->getMockBuilder(Generator::class)
            ->getMockForAbstractClass();
        /* @var $generator Generator */

        $additionalEvents = new EventStream();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods([
                'saveEventStream'
            ])
            ->getMockForAbstractClass();
        $eventRepository->expects($this->once())
            ->method('saveEventStream')
            ->with($additionalEvents);
        /* @var $eventRepository EventRepository */

        $eventBus = $this->getMockBuilder(EventBus::class)
            ->setMethods([
                'emmit'
            ])
            ->getMockForAbstractClass();
        $eventBus->expects($this->once())
            ->method('emmit')
            ->with($additionalEvents);
        /* @var $eventBus EventBus */

        $commandHandler = $this->getMockBuilder(CommandHandler::class)
            ->setConstructorArgs([
                $generator
            ])
            ->setMethods([
                'getAdditionalEvents',
                'handle'
            ])
            ->getMockForAbstractClass();
        $commandHandler->method('getAdditionalEvents')
            ->willReturn($additionalEvents);
        $commandHandler->method('handle')
            ->willThrowException(new HandlerException());
        /* @var $commandHandler CommandHandler */

        $command = $this->getMockBuilder(Command::class)
            ->setMockClassName('CommandMock')
            ->getMockForAbstractClass();
        /* @var $command Command */

        $commandBus = new CommandBus($generator, $eventRepository, $eventBus);
        $commandBus->registerHandler('CommandMock', $commandHandler);
        $commandBus->handle($command);

    }

}
