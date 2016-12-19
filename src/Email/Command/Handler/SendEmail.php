<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Email\Command\Handler;


use Shop\Command\Command;
use Shop\Command\Handler\CommandHandler;
use Shop\Email\Email;
use Shop\Email\Event\EmailHasBeenSent;
use Shop\Email\Event\EmailHasNotBeenSent;
use Shop\Email\Exception;
use Shop\Email\Sender\CannotSendEmailException;
use Shop\EventAggregate\EventAggregate;

class SendEmail extends CommandHandler
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var Exception
     */
    private $exception;

    /**
     * @param Command|\Shop\Email\Command\SendEmail $command
     * @return EventAggregate
     */
    public function handle(Command $command): EventAggregate
    {
        $this->email = $command->getEmail();
        try {
            $command->getEmailSenderService()->send($command->getEmail());
        } catch (CannotSendEmailException $cannotSendEmailException) {
            $this->exception = $cannotSendEmailException;
        }

        if ($this->isSent()) {
            $this->email->apply(new EmailHasBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->getEmail()
            ));
        } else {
            $this->email->apply(new EmailHasNotBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $this->getEmail(),
                $this->getException()->getMessage()
            ));
        }

        return $this->email;
    }

    private function isSent(): bool
    {
        return !$this->getException() instanceof Exception;
    }

    /**
     * @return Exception
     */
    private function getException(): Exception
    {
        return $this->exception;
    }

    /**
     * @return Email
     */
    private function getEmail(): Email
    {
        return $this->email;
    }


}