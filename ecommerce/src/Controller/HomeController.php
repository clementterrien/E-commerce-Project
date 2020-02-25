<?php

namespace App\Controller;


use App\Service\Email\Louise;
use Symfony\Component\Mime\Email;
use App\Service\Product\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductService $productService)
    {
        $productService->getTop3MostLikedProducts();

        return $this->render('/home/home.html.twig');
    }

    /**
     * @Route("/hubert", name="hubert")
     */
    public function hubzert(MailerInterface $mailer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $id = $user->getId();
        $token = $user->getConfirmationToken();

        if ($token == "") {
            $user->setConfirmationToken("$2y$1391z2trzGQQ5oAOMQwu.O7CazfodjG5wbtkVX5fKdD7o1IAGUES");
        }

        $email = (new TemplatedEmail())
            ->from('hello@symfony.com')
            ->to('clem@symfony.com')
            ->subject('It\'s a test mail')
            ->htmlTemplate('emails/registeringConfirmation.html.twig')
            ->context([
                'link' => 'http://localhost:8000/confirm/' . $id . '/' . $token
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('home');
    }
}
