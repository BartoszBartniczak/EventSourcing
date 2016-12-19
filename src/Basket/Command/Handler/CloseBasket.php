<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command\Handler;


use Shop\Basket\Event\BasketHasBeenClosed;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;

class CloseBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command)
    {
        /* @var $command \Shop\Basket\Command\CloseBasket */
        $command->getBasket()
            ->apply(
                new BasketHasBeenClosed(
                    $this->generateEventId(),
                    $this->generateDateTime(),
                    $command->getBasket()
                )
            );

        return $command->getBasket();
    }


}