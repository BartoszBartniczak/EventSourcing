<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;

use Shop\Event\Event;


class JSONSerializer implements Serializer
{

    /**
     * @var array
     */
    protected $handlers;

    /**
     * JsmJSONSerializer constructor.
     */
    public function __construct()
    {
    }

    public function registerHandler(string $eventClassName, Handler $handler)
    {
        $this->handlers[$eventClassName] = $handler;
    }

    public function serialize(Event $event): string
    {
        $handler = $this->findHandler(get_class($event));
        $serializedEvent = $handler->serialize($event);
        return json_encode($serializedEvent);
    }

    /**
     * @param string $className
     * @return Handler
     * @throws CannotFindHandlerException
     */
    private function findHandler(string $className): Handler
    {
        if (!isset($this->handlers[$className])) {
            throw new CannotFindHandlerException(sprintf("The Handler for the class '%s' is not registered.", $className));
        }
        return $this->handlers[$className];
    }

    public function deserialize($data): Event
    {
        $data = json_decode($data, true);
        $handler = $this->findHandler($data[Handler::$EVENT_NAME]);
        return $handler->deserialize($data);
    }


}