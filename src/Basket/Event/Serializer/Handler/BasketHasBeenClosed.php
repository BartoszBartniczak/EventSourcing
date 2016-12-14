<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event\Serializer\Handler;


use Shop\Basket\Basket;
use Shop\Event\Event;

class BasketHasBeenClosed extends Handler
{
    /**
     * @param Event|\Shop\Basket\Event\BasketHasBeenClosed $event
     * @return array
     */
    public function serialize(Event $event): array
    {

        $serializedData = $this->serializeEventHeaderToArray($event);

        $serializedData['basket'] = $this->serializeBasketConstructorArgs($event->getBasket());

        return $serializedData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);
        $deserializeConstructorArgs = $this->deserializeConstructorArgs($data['basket']);

        return new \Shop\Basket\Event\BasketHasBeenClosed(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            new Basket($deserializeConstructorArgs['id'], $deserializeConstructorArgs['ownerEmail'])
        );
    }


}