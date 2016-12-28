<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\ArrayObject;


class ArrayObject extends \ArrayObject
{

    /**
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from array is returned into the result ArrayObject. Array keys are preserved.
     * @param callable $callback
     * @return ArrayObject
     */
    public function filter(callable $callback): ArrayObject
    {
        $arrayCopy = $this->getArrayCopy();
        $filteredData = array_filter($arrayCopy, $callback);
        return new ArrayObject($filteredData);
    }

    public function shift()
    {
        $arrayCopy = $this->getArrayCopy();
        $firstElement = array_shift($arrayCopy);
        $this->exchangeArray($arrayCopy);
        return $firstElement;
    }

}