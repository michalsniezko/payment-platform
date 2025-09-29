<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\EmailStatus;
use App\Models\Email as EmailModel;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;

class EmailService
{
    public function __construct(protected EmailModel $emailModel, protected MailerInterface $mailer)
    {
    }

    public function sendQueuedEmails(): void
    {
        $emails = $this->emailModel->getEmailsByStatus(EmailStatus::QUEUE);

        foreach ($emails as $email) {
            $meta = json_decode($email->meta, true);

            $emailMessage = new SymfonyEmail()
                ->from($meta['from'])
                ->to($meta['to'])
                ->subject($email->subject)
                ->text($email->text_body)
                ->html($email->html_body);

            $this->mailer->send($emailMessage);

            $this->emailModel->markEmailSent($email->id);
        }
    }
}
