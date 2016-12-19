<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


use Ramsey\Uuid\Uuid;

final class SaltGenerator
{

    /**
     * @return string
     */
    public function generate(): string
    {
        return Uuid::uuid4();
    }

}