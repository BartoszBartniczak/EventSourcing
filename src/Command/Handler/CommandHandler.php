<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Handler;


use BartoszBartniczak\EventSourcing\Shop\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Command\CommandList;
use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;

abstract class CommandHandler
{

    /**
     * @var UUIDGenerator;
     */
    protected $uuidGenerator;

    /**
     * @var CommandList;
     */
    private $nextCommands;

    /**
     * Additional events are not connected with EventAggregate. Although they are emitted to EventBus.
     * @var  \Shop\Event\EventStream
     */
    private $additionalEvents;

    /**
     * CommandHandler constructor.
     * @param UUIDGenerator $uuidGenerator
     */
    final public function __construct(UUIDGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->nextCommands = new CommandList();
        $this->additionalEvents = new EventStream();
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
     * @return EventStream
     */
    public function getAdditionalEvents(): EventStream
    {
        return $this->additionalEvents;
    }

    /**
     * @param Command $command
     */
    protected function addNextCommand(Command $command)
    {
        $this->nextCommands[] = $command;
    }

    /**
     * @param Event $event
     */
    protected function addAdditionalEvent(Event $event)
    {
        $this->additionalEvents[] = $event;
    }

    /**
     * @return Id
     */
    protected function generateEventId(): Id
    {
        return new Id($this->uuidGenerator->generate()->toNative());
    }

    /**
     * @return \DateTime
     */
    protected function generateDateTime(): \DateTime
    {
        return new \DateTime();
    }

}