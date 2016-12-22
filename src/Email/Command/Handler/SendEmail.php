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
use Shop\Email\Sender\CannotSendEmailException;
use Shop\Email\Sender\Exception;

class SendEmail extends CommandHandler
{

    /**
     * @var Exception
     */
    private $exception;

    /**
     * @param Command|\Shop\Email\Command\SendEmail $command
     * @return Email
     */
    public function handle(Command $command): Email
    {
        try {
            $command->getEmailSenderService()->send($command->getEmail());
        } catch (CannotSendEmailException $cannotSendEmailException) {
            $this->exception = $cannotSendEmailException;
        }

        if ($this->isSent()) {
            $command->getEmail()->apply(new EmailHasBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getEmail()
            ));
        } else {
            $command->getEmail()->apply(new EmailHasNotBeenSent(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getEmail(),
                $this->exception->getMessage()
            ));
        }

        return $command->getEmail();
    }

    private function isSent(): bool
    {
        return !$this->exception instanceof Exception;
    }

}