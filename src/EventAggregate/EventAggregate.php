<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\EventAggregate;


use Shop\Event\Dispatcher\Dispatcher as EventDispatcher;
use Shop\Event\Event;
use Shop\Event\Handler\EventHandler;

abstract class EventAggregate
{

    const NAMESPACE_SEPARATOR = '\\';

    /**
     * @var EventStream
     */
    protected $commitedEvents;

    /**
     * @var EventStream
     */
    protected $uncommitedEvents;

    /**
     * EventAggregate constructor.
     */
    public function __construct()
    {
        $this->commitedEvents = new EventStream();
        $this->uncommitedEvents = new EventStream();
    }

    final public function applyEventStream(EventStream $stream): EventAggregate
    {
        foreach ($stream as $event) {
            $this->apply($event);
        }
        return $this;
    }

    final public function apply(Event $event): EventAggregate
    {
        $this->handle($event);
        $this->uncommitedEvents[] = $event;
        return $this;
    }

    /**
     * @param Event $event
     * @return void
     * @throws CannotHandleTheEventException
     */
    final protected function handle(Event $event)
    {
        $handleMethodName = $this->findHandleMethod($event);
        if (method_exists($this, $handleMethodName)) {
            $this->$handleMethodName($event);
            return;
        }

        throw new CannotHandleTheEventException(sprintf("Method '%s' does not exists.", $handleMethodName));
    }

    final protected function findHandleMethod(Event $event): string
    {
        $className = get_class($event);
        $separator = self::NAMESPACE_SEPARATOR;
        $arr = explode($separator, $className);
        return 'handle' . end($arr);
    }

    final public function commit()
    {
        foreach ($this->getUncommitedEvents() as $event) {
            $this->getCommitedEvents()[] = $event;
        }
        $this->uncommitedEvents = new EventStream();
    }

    final public function getUncommitedEvents(): EventStream
    {
        return $this->uncommitedEvents;
    }

    final public function getCommitedEvents(): EventStream
    {
        return $this->commitedEvents;
    }

}