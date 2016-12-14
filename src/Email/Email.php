<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email;


use Shop\Email\Event\EmailHasBeenSent;
use Shop\Email\Event\EmailHasNotBeenSent;
use Shop\EventAggregate\EventAggregate;
use Shop\UUID\UUID;

class Email extends EventAggregate
{
    /**
     * @var UUID
     */
    private $id;

    /**
     * @var bool
     */
    private $sent;

    /**
     * Email constructor.
     * @param UUID $id
     */
    public function __construct(UUID $id)
    {
        parent::__construct();
        $this->id = $id;
        $this->sent = false;
    }

    /**
     * @return UUID
     */
    public function getId(): UUID
    {
        return $this->id;
    }

    public function handleEmailHasBeenSent(EmailHasBeenSent $event)
    {
        $this->markAsSent();
    }

    protected function markAsSent()
    {
        $this->sent = true;
    }

    public function handleEmailHasNotBeenSent(EmailHasNotBeenSent $event)
    {

    }

}