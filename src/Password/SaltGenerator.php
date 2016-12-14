<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


final class SaltGenerator
{

    /**
     * @return string
     */
    public function generate(): string
    {
        return uniqid();
    }

}