<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event\Serializer\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\ProductHasBeenAddedToTheBasket as ProductHasBeenAddedToTheBasketEvent;
use Shop\Basket\Id;
use Shop\Event\Event;
use Shop\Event\Serializer\Handler;
use Shop\Product\Product;
use Shop\UUID\UUID;

class ProductHasBeenAddedToTheBasket extends Handler
{
    public function serialize(Event $event): array
    {
        /* @var $event \Shop\Basket\Event\ProductHasBeenAddedToTheBasket */
        $serializedData = $this->serializeEventHeaderToArray($event);
        $serializedData['basket'] = [
            'id' => $event->getBasket()->getId()->toNative(),
            'ownerEmail' => $event->getBasket()->getOwnerEmail()
        ];
        $serializedData['product'] = [
            'id' => $event->getProduct()->getId()->toNative(),
            'name' => $event->getProduct()->getName()
        ];
        $serializedData['quantity'] = $event->getQuantity();

        return $serializedData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);
        return new ProductHasBeenAddedToTheBasketEvent(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            new Basket(new Id($data['basket']['id']), $data['basket']['ownerEmail']),
            new Product(new UUID($data['product']['id']), $data['product']['name']),
            (float)$data['quantity']
        );
    }

}