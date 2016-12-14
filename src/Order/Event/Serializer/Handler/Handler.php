<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Event\Serializer\Handler;

use Shop\Event\Serializer\Handler as BasicHandler;
use Shop\Product\Product;
use Shop\UUID\UUID;

abstract class Handler extends BasicHandler
{

    /**
     * @param array $positions
     * @return array
     */
    protected function serializePositions(array $positions): array
    {
        $serializedData = [];

        foreach ($positions as $id => $position) {
            $serializedData[$id] = [
                'product' => $this->serializeProduct($position['product']),
                'quantity' => $this->serializeQuantity($position['quantity'])
            ];
        }

        return $serializedData;
    }

    /**
     * @param Product $product
     * @return array
     */
    protected function serializeProduct(Product $product): array
    {
        return [
            'id' => $product->getId()->toNative(),
            'name' => $product->getName()
        ];
    }

    /**
     * @param float $quantity
     * @return float
     */
    protected function serializeQuantity(float $quantity): float
    {
        return $quantity;
    }

    /**
     * @param array $positions
     * @return array
     */
    protected function deserializePositions(array $positions): array
    {
        $data = [];

        foreach ($positions as $position) {
            $product = $this->deserializeProduct($position['product']);
            $data[$product->getId()->toNative()] = [
                'product' => $product,
                'quantity' => $this->deserializeQuantity($position['quantity'])
            ];
        }

        return $data;
    }

    /**
     * @param array $product
     * @return Product
     */
    protected function deserializeProduct(array $product): Product
    {
        return new Product(
            new UUID($product['id']),
            $product['name']
        );
    }

    /**
     * @param $quantity
     * @return float
     */
    protected function deserializeQuantity($quantity): float
    {
        return (float)$quantity;
    }


}