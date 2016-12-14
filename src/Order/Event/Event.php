<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Order\Event;

use Shop\Event\Event as BasicEvent;

class Event extends BasicEvent
{

    const FAMILY_NAME = 'Order';

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return self::FAMILY_NAME;
    }


}