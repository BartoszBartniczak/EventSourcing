<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event\Serializer\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Id;

abstract class Handler extends \Shop\Event\Serializer\Handler
{

    protected function serializeBasketConstructorArgs(Basket $basket): array
    {
        return [
            'id' => $this->serializeBasketId($basket->getId()),
            'ownerEmail' => $this->serializeOwnerEmail($basket->getOwnerEmail())
        ];
    }

    /**
     * @param Id $id
     * @return string
     */
    private function serializeBasketId(Id $id): string
    {
        return $id->toNative();
    }

    /**
     * @param string $getOwnerEmail
     * @return string
     */
    private function serializeOwnerEmail(string $getOwnerEmail): string
    {
        return $getOwnerEmail;
    }

    protected function deserializeConstructorArgs(array $data)
    {
        return [
            'id' => $this->deserializeId($data['id']),
            'ownerEmail' => $this->deserializeOwnerEmail($data['ownerEmail']),
        ];
    }

    private function deserializeId(string $id): Id
    {
        return new Id($id);
    }

    private function deserializeOwnerEmail(string $ownerEmail): string
    {
        return $ownerEmail;
    }

}