<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\EventAggregate;


use Shop\Event\Event;

abstract class EventAggregate
{

    const NAMESPACE_SEPARATOR = '\\';

    /**
     * @var EventStream
     */
    protected $committedEvents;

    /**
     * @var EventStream
     */
    protected $uncommittedEvents;

    /**
     * EventAggregate constructor.
     */
    public function __construct()
    {
        $this->committedEvents = new EventStream();
        $this->uncommittedEvents = new EventStream();
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
        $this->uncommittedEvents[] = $event;
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
        foreach ($this->getUncommittedEvents() as $event) {
            $this->getCommittedEvents()[] = $event;
        }
        $this->uncommittedEvents = new EventStream();
    }

    final public function getUncommittedEvents(): EventStream
    {
        return $this->uncommittedEvents;
    }

    final public function getCommittedEvents(): EventStream
    {
        return $this->committedEvents;
    }

}