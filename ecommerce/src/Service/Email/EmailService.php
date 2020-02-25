<?php

namespace App\Service\Email;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    protected $mailer;

    public function __construct(MailerInterface $mailer, UserRepository $userRepo)
    {
        $this->mailer = $mailer;
        $this->userRepo = $userRepo;
    }

    public function sendRegistrationConfirmEmail($user)
    {
        $user = $this->userRepo->findOneBy(['email' => $user->getEmail()]);

        $email = (new TemplatedEmail())
            ->from('noreply@symfony.com')
            ->to($user->getEmail())
            ->subject('Thanks for registering - Now Confirm your email')
            ->htmlTemplate('emails/registeringConfirmation.html.twig')
            ->context([
                'link' => 'http://localhost:8000/confirm/' . $user->getId() . '/' . $user->getConfirmationToken()
            ]);

        $this->mailer->send($email);
    }
}
