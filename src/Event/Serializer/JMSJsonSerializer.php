<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;

use BartoszBartniczak\EventSourcing\Event\Event;
use JMS\Serializer\Serializer as JMSSerializer;


class JMSJsonSerializer implements Serializer
{

    /**
     * @var JMSSerializer
     */
    private $jmsSerializer;

    /**
     * JMSJsonSerializer constructor.
     * @param JMSSerializer $jmsSerializer
     */
    public function __construct(JMSSerializer $jmsSerializer)
    {
        $this->jmsSerializer = $jmsSerializer;
    }

    /**
     * @param Event $event
     * @return string
     */
    public function serialize(Event $event): string
    {
        return $this->jmsSerializer->serialize($event, 'json');
    }

    /**
     * @param $data
     * @return Event
     */
    public function deserialize($data): Event
    {
        $className = $this->tryToExtractClassName($data);
        return $this->jmsSerializer->deserialize($data, $className, 'json');
    }

    /**
     * @param $data
     * @return string
     */
    private function tryToExtractClassName($data): string
    {
        $data = json_decode($data, true);
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Cannot extract class name of the event');
        }
        return $data['name'];
    }

}