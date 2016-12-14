<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Command;


use Shop\Command\Command;
use Shop\Password\HashGenerator;
use Shop\User\Repository\UserRepository;

class LogInUser implements Command
{

    /**
     * @var string
     */
    private $password;

    /**
     * @var HashGenerator
     */
    private $hashGenerator;

    /** @var string */
    private $email;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LogInUser constructor.
     * @param string $email
     * @param string $password
     * @param HashGenerator $hashGenerator
     * @param UserRepository $userRepository
     */
    public function __construct(string $email, string $password, HashGenerator $hashGenerator, UserRepository $userRepository)
    {
        $this->email = $email;
        $this->password = $password;
        $this->hashGenerator = $hashGenerator;
        $this->userRepository = $userRepository;

    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return HashGenerator
     */
    public function getHashGenerator(): HashGenerator
    {
        return $this->hashGenerator;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

}