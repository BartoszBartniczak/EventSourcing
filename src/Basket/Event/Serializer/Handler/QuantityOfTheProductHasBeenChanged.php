<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event\Serializer\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\QuantityOfTheProductHasBeenChanged as QuantityOfTheProductHasBeenChangedEvent;
use Shop\Basket\Id;
use Shop\Event\Event;
use Shop\Event\Serializer\Handler;
use Shop\UUID\UUID;

class QuantityOfTheProductHasBeenChanged extends Handler
{
    /**
     * @param Event|\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged $event
     * @return array
     */
    public function serialize(Event $event): array
    {
        $serializedArray = $this->serializeEventHeaderToArray($event);
        $serializedArray['basket'] = [
            'id' => $event->getBasket()->getId()->toNative(),
            'ownerEmail' => $event->getBasket()->getOwnerEmail()
        ];
        $serializedArray['product']['id'] = $event->getProductId()->toNative();
        $serializedArray['quantity'] = $event->getQuantity();
        return $serializedArray;
    }

    /**
     * @param array $data
     * @return Event
     */
    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);
        return new QuantityOfTheProductHasBeenChangedEvent(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            new Basket(new Id($data['basket']['id']), $data['basket']['ownerEmail']),
            new UUID($data['product']['id']),
            (float)$data['quantity']
        );
    }


}