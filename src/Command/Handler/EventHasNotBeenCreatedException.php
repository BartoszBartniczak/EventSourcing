<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Command\Handler;

use BartoszBartniczak\CQRS\Command\Handler\Exception;

class EventHasNotBeenCreatedException extends Exception
{

}