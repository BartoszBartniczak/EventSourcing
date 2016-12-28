<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command;


use BartoszBartniczak\EventSourcing\Shop\ArrayObject\ArrayOfObjects;

class CommandList extends ArrayOfObjects
{

    /**
     * CommandList constructor.
     * @param array|null $items
     */
    public function __construct(array $items = null)
    {
        parent::__construct(Command::class, $items);
    }

}