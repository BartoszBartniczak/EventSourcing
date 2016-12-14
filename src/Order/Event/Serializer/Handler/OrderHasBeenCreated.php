<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Event\Serializer\Handler;


use Shop\Basket\Id as BasketId;
use Shop\Event\Event;
use Shop\Order\Id as OrderId;

class OrderHasBeenCreated extends Handler
{
    public function serialize(Event $event): array
    {
        /* @var $event \Shop\Order\Event\OrderHasBeenCreated */

        $eventData = $this->serializeEventHeaderToArray($event);
        $eventData['order'] = [
            'id' => $event->getOrderId()->toNative(),
            'basketId' => $event->getBasketId()->toNative(),
            'positions' => $this->serializePositions($event->getPositions())
        ];
        return $eventData;
    }

    public function deserialize(array $data): Event
    {
        $header = $this->deserializeEventHeader($data);

        return new \Shop\Order\Event\OrderHasBeenCreated(
            $header[self::$EVENT_ID],
            $header[self::$EVENT_DATE_TIME],
            new OrderId($data['order']['id']),
            new BasketId($data['order']['basketId']),
            $this->deserializePositions($data['order']['positions'])
        );
    }

}