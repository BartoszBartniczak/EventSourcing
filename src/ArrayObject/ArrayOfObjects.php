<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\ArrayObject;

/**
 * Class ArrayOfElements can contain only objects of given type
 * @package Shop\ArrayObject
 */
class ArrayOfObjects extends \BartoszBartniczak\ArrayObject\ArrayOfObjects
{

    /**
     * ArrayOfElements constructor.
     * @param string $className
     * @param array|null $items
     */
    public function __construct(string $className, array $items = null)
    {
        parent::__construct($className, $items);
    }

    /**
     * Pop the element off the end of array
     * @return mixed
     */
    public function pop()
    {
        $arrayCopy = $this->getArrayCopy();
        $element = array_pop($arrayCopy);
        $this->exchangeArray($arrayCopy);

        return $element;
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        $arrayCopy = $this->getArrayCopy();
        $element = array_shift($arrayCopy);
        $this->exchangeArray($arrayCopy);
        return $element;
    }

    /**
     * @param ArrayOfObjects $arrayOfObjects
     */
    public function merge(ArrayOfObjects $arrayOfObjects)
    {
        $arrayCopy = $this->getArrayCopy();
        $arrayToMerge = $arrayOfObjects->getArrayCopy();
        $this->exchangeArray(array_merge($arrayCopy, $arrayToMerge));
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->isNotEmpty();
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from array is returned into the result ArrayOfObjects. Array keys are preserved.
     * @param callable $callback
     * @return ArrayOfObjects
     */
    public function filter(callable $callback): ArrayOfObjects
    {
        $arrayCopy = $this->getArrayCopy();
        $filteredData = array_filter($arrayCopy, $callback);
        return new ArrayOfObjects($this->getClassName(), $filteredData);
    }

    /**
     * @inheritDoc
     */
    protected function throwExceptionIfObjectIsNotInstanceOfTheClass($object)
    {
        if (!$object instanceof $this->className) {
            throw new InvalidArgumentException(
                sprintf("Instance of '\%s' expected. '\%s' given.", $this->getClassName(), get_class($object))
            );
        }
    }


}