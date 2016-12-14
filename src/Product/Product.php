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
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * Product constructor.
     * @param UUID $uuid
     * @param string $name
     */
    public function __construct(UUID $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
    }

    /**
     * @return UUID
     */
    public function getId(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}