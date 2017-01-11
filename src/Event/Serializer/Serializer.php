<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


use BartoszBartniczak\EventSourcing\Event\Event;

interface Serializer
{

    public function serialize(Event $event): string;

    public function deserialize($data): Event;

}