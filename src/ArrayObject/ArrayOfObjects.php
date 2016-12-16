<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\ArrayObject;

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
    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
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