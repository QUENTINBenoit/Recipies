<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    /** 
     * @param MailerInterface $mailer
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * sendMail
     *
     * @param string $from
     * @param string $subject
     * @param string $htmlTemplate
     * @param array $context
     * @param string $to
     * @return void
     */
    public function sendEmail(
        string $from,
        string $subject,
        string $htmlTemplate,
        array $context,
        string $to
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);
        $this->mailer->send($email);
    }
}
