<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User\Factory;


use Shop\User\User;

class Factory
{

    public function createEmpty(): User
    {
        return new User('', '', '');
    }

}