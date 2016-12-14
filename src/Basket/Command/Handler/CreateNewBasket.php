<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command\Handler;


use Shop\Basket\Basket;
use Shop\Basket\Event\BasketHasBeenCreated;
use Shop\Basket\Id;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\EventAggregate\EventAggregate;

class CreateNewBasket extends CommandHandler
{

    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \Shop\Basket\Command\CreateNewBasket */
        $basket = new Basket(
            new Id($command->getGeneratorUUID()->generate()->toNative()),
            $command->getUserEmail()
        );

        $basket->apply(
            new BasketHasBeenCreated(
                $this->uuidGenerator->generate(),
                new \DateTime(),
                $basket
            )
        );

        return $basket;
    }

}