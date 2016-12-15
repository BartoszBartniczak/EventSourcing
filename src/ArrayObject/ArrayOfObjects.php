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
class ArrayOfObjects extends \ArrayObject\ArrayOfObjects
{

    /**
     * ArrayOfElements constructor.
     * @param string $className
     */
    public function __construct(string $className)
    {
        parent::__construct($className);
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

}