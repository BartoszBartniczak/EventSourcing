<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Event;


use Shop\Basket\Basket;

class Event extends \Shop\Event\Event
{

    const FAMILY_NAME = 'Basket';

    /**
     * @var Basket
     */
    protected $basket;

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     */
    protected function setBasket(Basket $basket)
    {
        $this->basket = $basket;
    }

}