<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


use Shop\User\User;

final class HashGenerator
{

    public function __construct()
    {

    }

    public function hash($password, $salt)
    {
        return password_hash($password . $salt, PASSWORD_DEFAULT);
    }

    public function verifyUserPassword(string $password, User $user): bool
    {
        return password_verify($password . $user->getPasswordSalt(), $user->getPasswordHash());
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        $hashInfo = $this->hashInfo($hash);
        return password_needs_rehash($hash, $hashInfo['algo']);
    }

    /**
     * @param string $hash
     * @return array
     */
    private function hashInfo(string $hash): array
    {
        return password_get_info($hash);
    }

}