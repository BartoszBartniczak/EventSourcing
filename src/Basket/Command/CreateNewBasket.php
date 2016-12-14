<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket\Command;


use Shop\Command\Command;
use Shop\UUID\Generator as GeneratorUUID;

class CreateNewBasket implements Command
{

    /**
     * @var GeneratorUUID
     */
    private $generatorUUID;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * CreateNewBasket constructor.
     * @param GeneratorUUID $generatorUUID
     * @param string $userEmail
     */
    public function __construct(GeneratorUUID $generatorUUID, string $userEmail)
    {
        $this->generatorUUID = $generatorUUID;
        $this->userEmail = $userEmail;
    }

    /**
     * @return GeneratorUUID
     */
    public function getGeneratorUUID(): GeneratorUUID
    {
        return $this->generatorUUID;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

}