<?php
// src/Service/EmailService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(string $userEmail, string $username, string $confirmationUrl)
    {
        $email = (new Email())
            ->from('sablyl@live.fr')  // L'email de l'expéditeur
            ->to($userEmail)          // L'email du destinataire
            ->subject('Confirmation d\'inscription')
            ->html("
                <html>
                    <body>
                        <h1>Bonjour $username!</h1>
                        <p>Merci pour votre inscription. Veuillez cliquer sur le lien ci-dessous pour confirmer votre adresse email.</p>
                        <a href='$confirmationUrl'>Confirmer mon email</a>
                    </body>
                </html>
            ");

        // Envoi de l'email via Mailtrap
        $this->mailer->send($email);
    }
}