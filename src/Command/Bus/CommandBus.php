<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Bus;


use BartoszBartniczak\EventSourcing\Shop\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Command\CommandList;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\Exception as HandlerException;
use BartoszBartniczak\EventSourcing\Shop\Command\Query;
use BartoszBartniczak\EventSourcing\Shop\Event\Bus\EventBus;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\EventRepository;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;

class CommandBus
{

    /**
     * @var EventBus
     */
    protected $eventBus;
    /**
     * @var array
     */
    private $commandHandlers;
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var UUIDGenerator
     */
    private $generator;
    /**
     * @var mixed
     */
    private $output;

    /**
     * CommandBus constructor.
     * @param UUIDGenerator $generator
     * @param EventRepository $eventRepository
     * @param EventBus $eventBus
     */
    public function __construct(UUIDGenerator $generator, EventRepository $eventRepository, EventBus $eventBus)
    {
        $this->commandHandlers = [];
        $this->eventRepository = $eventRepository;
        $this->generator = $generator;
        $this->clearOutput();
        $this->eventBus = $eventBus;
    }

    /**
     * @return void
     */
    private function clearOutput()
    {
        $this->output = null;
    }

    /**
     * @param string $commandClassName
     * @param CommandHandler $commandHandler
     */
    public function registerHandler(string $commandClassName, CommandHandler $commandHandler)
    {
        $this->commandHandlers[$commandClassName] = $commandHandler;
    }

    /**
     * @param Command $command
     * @throws CannotHandleTheCommandException
     * @throws CannotFindHandlerException
     * @return mixed
     */
    public function handle(Command $command)
    {
        if ($command instanceof Query) {
            return $this->handleQuery($command);
        } else {
            $this->handleCommand($command);
        }
    }

    /**
     * @param Query $query
     * @throws CannotHandleTheCommandException
     * @throws CannotFindHandlerException
     * @return mixed
     */
    protected function handleQuery(Query $query)
    {
        $this->clearOutput();
        $handler = $this->findHandler($query);
        $eventAggregate = $this->tryToHandleCommand($query, $handler);

        $this->saveOutput($eventAggregate);

        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);

        $this->eventBus->emmit($additionalEvents);
        return $this->output;
    }

    /**
     * @param Command $command
     * @return CommandHandler
     * @throws CannotFindHandlerException
     */
    protected function findHandler(Command $command): CommandHandler
    {
        $className = get_class($command);

        if (isset($this->commandHandlers[$className]) && $this->commandHandlers[$className] instanceof CommandHandler) {
            return $this->commandHandlers[$className];
        } else {
            throw new CannotFindHandlerException(sprintf("Cannot find handler for command: '%s'.", get_class($command)));
        }
    }

    /**
     * @param Command $command
     * @param CommandHandler $handler
     * @return mixed
     * @throws CannotHandleTheCommandException
     */
    private function tryToHandleCommand(Command $command, CommandHandler $handler)
    {
        try {
            $eventAggregate = $handler->handle($command);
            return $eventAggregate;
        } catch (HandlerException $handlerException) {
            $this->handleHandlerException($handler);
            throw new CannotHandleTheCommandException(sprintf("Command '%s' cannot be handled.", get_class($command)), null, $handlerException);
        }
    }

    /**
     * @param CommandHandler $handler
     * @throws CannotHandleTheCommandException
     */
    private function handleHandlerException(CommandHandler $handler)
    {
        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);
        $this->eventBus->emmit($additionalEvents);
    }

    /**
     * @param $data
     */
    private function saveOutput($data)
    {
        $this->output = $data;
    }

    /**
     * @param Command $command
     * @throws CannotHandleTheCommandException
     * @throws CannotFindHandlerException
     */
    protected function handleCommand(Command $command)
    {
        $handler = $this->findHandler($command);
        $data = $this->tryToHandleCommand($command, $handler);

        if ($data instanceof EventAggregate) {
            $eventsToEmmit = clone $data->getUncommittedEvents();
            $this->saveDataInRepository($data);
        } else {
            $eventsToEmmit = new EventStream();
        }

        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);

        $eventsToEmmit->merge($additionalEvents);
        $this->eventBus->emmit($eventsToEmmit);

        $this->passNextCommandsToTheBus($handler->getNextCommands());
    }

    /**
     * @param $eventAggregate
     */
    private function saveDataInRepository(EventAggregate $eventAggregate)
    {
        $this->eventRepository->saveEventAggregate($eventAggregate);
    }

    /**
     * @param CommandList $commandList
     */
    private function passNextCommandsToTheBus(CommandList $commandList)
    {
        if ($commandList->isNotEmpty()) {
            foreach ($commandList as $nextCommand)
                $this->handle($nextCommand);
        }
    }


}