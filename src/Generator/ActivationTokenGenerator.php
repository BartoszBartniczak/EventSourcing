<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Generator;


class ActivationTokenGenerator implements Generator
{
    public function generate()
    {
        return uniqid();
    }

}