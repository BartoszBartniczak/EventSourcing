<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command\Handler;


use Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\EventAggregate\EventAggregate;

class AddProductToTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \Shop\Basket\Command\AddProductToTheBasket */

        $command->getBasket()->apply(
            new ProductHasBeenAddedToTheBasket(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket(),
                $command->getProduct(),
                $command->getQuantity())
        );

        return $command->getBasket();
    }

}