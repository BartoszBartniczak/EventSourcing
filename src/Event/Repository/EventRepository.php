<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Event\Repository;


use Shop\EventAggregate\EventAggregate;

interface EventRepository
{

    public function save(EventAggregate $eventAggregate);


}