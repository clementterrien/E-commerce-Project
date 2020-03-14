<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Product\ProductService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProductService $productService, PaginatorInterface $paginator, Request $request)
    {

        $formQuery = $request->query->all();
        $key = key($formQuery);

        $filteredProducts = null;

        dump($key);
        if (!is_null($key) && $key !== "page") {
            $value = $request->query->get($key);
            $filteredProducts = $paginator->paginate($productService->getProductsByCriteria($key, $value), $request->query->getInt('page', 1));
        }


        $topThreeProducts = $productService->getTop3MostLikedProducts();
        $allTheProducts = $paginator->paginate(
            $productService->getAllTheProducts(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('/home/home.html.twig', [
            "topThreeProducts" => $topThreeProducts,
            "allTheProducts" => $allTheProducts,
            "filteredProducts" => $filteredProducts
        ]);
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

    /**
     * @Route("/tester", name="test_tester")
     */
    public function testament()
    {
        return $this->render('test/test.html.twig');
    }
}
