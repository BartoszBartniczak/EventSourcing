<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CannotHandleTheCommandException;
use Shop\Command\Handler\CommandHandler;
use Shop\Product\Repository\CannotFindProductException;
use Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use Shop\Product\Repository\Event\ProductHasNotBeenFound;

class FindProductByName extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
        /* @var $command FindProductByNameCommand */

        try {
            $product = $command->getProductRepository()->findByName($command->getProductName());
            return $product;
        } catch (CannotFindProductException $cannotFindProductException) {
            $this->addAdditionalEvent(
                new ProductHasNotBeenFound(
                    $this->uuidGenerator->generate(),
                    new \DateTime(),
                    $command->getProductName(),
                    $command->getUser()->getEmail()
                )
            );
            throw new CannotHandleTheCommandException("Product has not been found in repository", null, $cannotFindProductException);
        }

    }

}