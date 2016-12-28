<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Bus;


use BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayObject;
use BartoszBartniczak\EventSourcing\Shop\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\Exception as HandlerException;
use BartoszBartniczak\EventSourcing\Shop\Event\Bus\EventBus;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\EventRepository;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;
use BartoszBartniczak\EventSourcing\Shop\UUID\UUID;

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
     * @var \Shop\Event\Repository\EventRepository
     */
    private $eventRepository;
    /**
     * @var UUIDGenerator
     */
    private $generator;
    /**
     * @var ArrayObject
     */
    private $output;
    /**
     * @var UUID
     */
    private $fingerprint;

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
        $this->output = new ArrayObject();
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
     * In general it handles command, but it does in in the given order:
     *
     * At the beginning it generates fingerprint. It is required for saving the output for all of commands from the chain.
     * Then, it finds the command handler and passes the command to it.
     * If the handler returns EventAggregate, the uncommitted events are saved in repository and stored for emission.
     * If the command generates additional events (not connected with EventAggregate), they are saved in repository and stored for emission.
     * Next, the output for the command is saved.
     * Then uncommitted events and additional events are emitted to EventBus.
     * At the end, next commands for execution are passed to the CommandBus.
     *
     * @param Command $command
     * @param UUID|null $fingerprint
     * @throws CannotHandleTheCommandException
     * @throws CannotFindHandlerException
     */
    public function handle(Command $command, UUID $fingerprint = null)
    {
        if (!$fingerprint instanceof UUID) {
            $fingerprint = $this->generator->generate();
            $this->fingerprint = $fingerprint;
            $this->clearOutput();
        }

        $handler = $this->findHandler($command);
        try {
            $eventAggregate = $handler->handle($command);
        } catch (HandlerException $handlerException) {
            $additionalEvents = $handler->getAdditionalEvents();
            $this->eventRepository->saveEventStream($additionalEvents);
            $this->eventBus->emmit($additionalEvents);
            throw new CannotHandleTheCommandException(sprintf("Command '%s' cannot be handled.", get_class($command)), null, $handlerException);
        }
        $eventsToEmmit = new EventStream();

        if ($eventAggregate instanceof EventAggregate) {
            $eventsToEmmit = clone $eventAggregate->getUncommittedEvents();
            $this->eventRepository->saveEventAggregate($eventAggregate);
            $eventAggregate->commit();
        }

        $additionalEvents = $handler->getAdditionalEvents();
        $eventsToEmmit->merge($additionalEvents);
        $this->eventRepository->saveEventStream($additionalEvents);

        $this->saveOutput($command, $eventAggregate);
        $this->eventBus->emmit($eventsToEmmit);
        $commandList = $handler->getNextCommands();
        if ($commandList->isNotEmpty()) {
            foreach ($commandList as $nextCommand)
                $this->handle($nextCommand, $fingerprint);
        }
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
     * @param $data
     */
    private function saveOutput(Command $command, $data)
    {
        $commandName = get_class($command);
        $this->output[$commandName] = $data;
    }

    /**
     * @return ArrayObject
     */
    public function getOutput(): ArrayObject
    {
        return $this->output;
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function getOutputForCommand(Command $command)
    {
        $commandName = get_class($command);
        return $this->output[$commandName];
    }


}