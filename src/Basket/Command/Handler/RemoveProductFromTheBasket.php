<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;

class RemoveProductFromTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Basket
    {
        /* @var $command \Shop\Basket\Command\RemoveProductFromTheBasket */
        $command->getBasket()->apply(
            new ProductHasBeenRemovedFromTheBasket($this->uuidGenerator->generate(), new \DateTime(), $command->getBasket(), $command->getProductId())
        );

        return $command->getBasket();
    }

}