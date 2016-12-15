<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product;


use Shop\UUID\UUID;

class Product
{

    /**
     * @var UUID
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * Product constructor.
     * @param UUID $id
     * @param string $name
     */
    public function __construct(UUID $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return UUID
     */
    public function getId(): UUID
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}