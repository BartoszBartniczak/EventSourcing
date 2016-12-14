<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\Product\Product;
use Shop\Product\Repository\CannotFindProductException;
use Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;

class FindProductByName extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Product
    {
        /* @var $command FindProductByNameCommand */

        try {
            $product = $command->getProductRepository()->findByName($command->getProductName());
        } catch (CannotFindProductException $cannotFindProductException) {
            /* @TODO ProductHasNotBeenFoundEvent */
        }

        return $product;
    }

}