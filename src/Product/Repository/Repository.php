<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository;


use Shop\Product\Product;
use Shop\UUID\UUID;

interface Repository
{

    /**
     * @param UUID $productId
     * @return Product
     * @throws CannotFindProductException
     */
    public function findById(UUID $productId): Product;

    /**
     * @param string $name
     * @return Product
     * @throws CannotFindProductException
     */
    public function findByName(string $name): Product;

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product);

}