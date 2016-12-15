<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;

use JMS\Serializer\Serializer as JMSSerializer;
use Shop\Event\Event;


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

    public function serialize(Event $event)
    {
        return $this->jmsSerializer->serialize($event, 'json');
    }

    public function deserialize($data): Event
    {
        $className = $this->tryToExtractClassName($data);
        return $this->jmsSerializer->deserialize($data, $className, 'json');
    }

    private function tryToExtractClassName($data)
    {
        $data = json_decode($data, true);
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException();
        }
        return $data['name'];
    }


}