<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event\Serializer\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\BasketHasBeenCreated;
use Shop\Basket\Id;
use Shop\Event\Event;
use Shop\Event\Serializer\Handler;

class BasketHasBeenCreatedHandler extends Handler
{
    public function serialize(Event $event): array
    {
        /* @var $event \Shop\Basket\Event\BasketHasBeenCreated */
        $serializedData = $this->serializeEventHeaderToArray($event);
        $serializedData['basket'] = [
            'id' => $event->getBasket()->getId()->toNative(),
            'ownerEmail' => $event->getBasket()->getOwnerEmail()
        ];
        return $serializedData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);
        return new BasketHasBeenCreated(
            $header[Handler::$EVENT_ID],
            $header[Handler::$EVENT_DATE_TIME],
            new Basket(
                new Id($data['basket']['id']),
                ($data['basket']['ownerEmail'])
            )
        );
    }


}