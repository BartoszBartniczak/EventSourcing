<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Serializer;


use Shop\Event\Event;

interface Serializer
{

    public function serialize(Event $event);

    public function deserialize($data): Event;

}