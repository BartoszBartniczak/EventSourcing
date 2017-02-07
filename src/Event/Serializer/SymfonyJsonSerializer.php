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
     * @var array
     */
    protected $contextGroups;

    /**
     * SymfonyJsonSerializer constructor.
     * @param SymfonySerializer $serializer
     * @param array $contextGroups
     */
    public function __construct(SymfonySerializer $serializer, array $contextGroups = array())
    {
        $this->symfonySerializer = $serializer;
        $this->contextGroups = $contextGroups;
    }

    public function serialize(Event $event): string
    {
        return $this->symfonySerializer->serialize($event, 'json', ['groups' => $this->getContextGroups()]);
    }

    /**
     * @return array
     */
    protected function getContextGroups(): array
    {
        return $this->contextGroups;
    }

    public function deserialize($data): Event
    {
        return $this->symfonySerializer->deserialize($data, $this->tryToExtractClassName($data), 'json', ['groups' => $this->getContextGroups()]);
    }


}