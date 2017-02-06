<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;

use BartoszBartniczak\EventSourcing\Event\Event;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class SymfonyJsonSerializer implements Serializer
{

    use ExtractClassNameTrait;

    /**
     * @var SymfonySerializer
     */
    protected $symfonySerializer;

    /**
     * SymfonyJsonSerializer constructor.
     * @param SymfonySerializer $serializer
     */
    public function __construct(SymfonySerializer $serializer)
    {
        $this->symfonySerializer = $serializer;
    }

    public function serialize(Event $event): string
    {
        return $this->symfonySerializer->serialize($event, 'json');
    }

    public function deserialize($data): Event
    {
        return $this->symfonySerializer->deserialize($data, $this->tryToExtractClassName($data), 'json');
    }


}