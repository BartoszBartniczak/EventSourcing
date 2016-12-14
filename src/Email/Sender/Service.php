<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Sender;


use Shop\Email\Email;

interface Service
{

    /**
     * @param Email $email
     * @return void
     * @throws CannotSendEmailException
     */
    public function send(Email $email);

}