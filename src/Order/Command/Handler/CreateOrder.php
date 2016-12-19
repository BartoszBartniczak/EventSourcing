<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Command\Handler;


use Shop\Basket\Command\CloseBasket;
use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\Email\Command\SendEmail;
use Shop\Order\Event\OrderHasBeenCreated;
use Shop\Order\Id;
use Shop\Order\Order;

class CreateOrder extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Order
    {
        /* @var $command \Shop\Order\Command\CreateOrder */

        $order = new Order(
            new Id($command->getUuidGenerator()->generate()->toNative()),
            $command->getBasket()->getId(),
            $command->getBasket()->getPositions()
        );

        $order->apply(
            new OrderHasBeenCreated(
                $this->generateEventId(),
                $this->generateDateTime(),
                $order->getOrderId(),
                $order->getBasketId(),
                $order->getPositions()
            )
        );

        $this->addNextCommand(new CloseBasket($command->getBasket()));
        $this->addNextCommand(new SendEmail($command->getEmailSenderService(), $command->getEmail()));

        return $order;
    }


}