<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Command\Handler;


use Shop\Command\Command;
use Shop\Command\CommandList;
use Shop\EventAggregate\EventAggregate;
use Shop\EventAggregate\EventStream;
use Shop\UUID\Generator as UUIDGenerator;

abstract class CommandHandler
{

    /**
     * @var UUIDGenerator;
     */
    protected $uuidGenerator;

    /**
     * @var EventStream
     */
    protected $eventStream;

    /**
     * @var CommandList;
     */
    private $nextCommands;

    /**
     * CommandHandler constructor.
     * @param UUIDGenerator $uuidGenerator
     */
    final public function __construct(UUIDGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->nextCommands = new CommandList(Command::class);
    }

    /**
     * @param Command $command
     * @return EventAggregate|void
     */
    abstract public function handle(Command $command);

    /**
     * @return CommandList
     */
    public function getNextCommands(): CommandList
    {
        return $this->nextCommands;
    }

    /**
     * @param Command $command
     */
    protected function addNextCommand(Command $command)
    {
        $this->nextCommands[] = $command;
    }

}