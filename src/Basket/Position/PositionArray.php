<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Position;


use Shop\ArrayObject\ArrayOfObjects;

class PositionArray extends ArrayOfObjects
{

    public function __construct(array $items = null)
    {
        parent::__construct(Position::class, $items);
    }

}