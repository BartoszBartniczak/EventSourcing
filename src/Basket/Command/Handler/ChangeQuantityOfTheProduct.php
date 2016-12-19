<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;

class ChangeQuantityOfTheProduct extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Basket
    {
        /* @var $command \Shop\Basket\Command\ChangeQuantityOfTheProduct */

        $command->getBasket()->apply(
            new QuantityOfTheProductHasBeenChanged(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket(),
                $command->getProductId(),
                $command->getQuantity())
        );

        return $command->getBasket();
    }


}