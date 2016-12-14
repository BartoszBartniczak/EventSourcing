<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Command\Bus;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\Event\Repository\EventRepository;
use Shop\EventAggregate\EventAggregate;
use Shop\UUID\Generator as UUIDGenerator;
use Shop\UUID\UUID;

class CommandBus
{

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
     * @var array
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
     */
    public function __construct(UUIDGenerator $generator, EventRepository $eventRepository)
    {
        $this->commandHandlers = [];
        $this->eventRepository = $eventRepository;
        $this->generator = $generator;
        $this->clearOutput();
    }

    /**
     * @return void
     */
    private function clearOutput()
    {
        $this->output = [];
    }

    /**
     * @param string $commandClassName
     * @param CommandHandler $commandHandler
     */
    public function registerHandler(string $commandClassName, CommandHandler $commandHandler)
    {
        $this->commandHandlers[$commandClassName] = $commandHandler;
    }

    public function handle(Command $command, UUID $fingerprint = null)
    {
        if (!$fingerprint instanceof UUID) {
            $fingerprint = $this->generator->generate();
            $this->fingerprint = $fingerprint;
            $this->clearOutput();
        }


        $handler = $this->findHandler($command);
        $eventAggregate = $handler->handle($command);
        if ($eventAggregate instanceof EventAggregate) {
            $this->eventRepository->save($eventAggregate);
            $eventAggregate->commit();
        }
        $this->saveOutput($command, $eventAggregate);
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
        $classname = get_class($command);

        if (isset($this->commandHandlers[$classname]) && $this->commandHandlers[$classname] instanceof CommandHandler) {
            return $this->commandHandlers[$classname];
        } else {
            throw new CannotFindHandlerException(sprintf('Cannot find handler for command: %s', get_class($command)));
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
     * @return array
     */
    public function getOutput(): array
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