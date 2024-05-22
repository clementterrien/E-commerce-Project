<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Data\StructureData;
use App\Data\SearchStructure;
use App\Service\Data\DataService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\Product\ProductService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, ProductService $productService, ProductRepository $productRepo)
    {
        $searchData = new SearchData;
        $searchData->page = $request->get('page', 1);


        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        [$min, $max] = $productRepo->findMinMaxPrice($searchData);


        return $this->render('/home/home.html.twig', [
            'top3GoodPlans' => $productService->getTop3GoodPlans(),
            'top3RedWineSelection' => $productService->getTop3RedWineSelection(),
            'top3GrandsCrusSelection' => $productService->getTop3GrandsCrusSelection(),
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max,
            'searchData' => $searchData
        ]);
    }

    /**
     * @Route("/search", name="home_search")
     */
    public function homeSearch(
        ProductRepository $productRepo,
        Request $request,
        ProductService $productService,
        CategoryRepository $catRepo,
        DataService $dataService,
        SessionInterface $session
    ) {
        $dataSearch = new SearchData;
        $dataSearch->page = $request->get('page', 1);

        $form = $this->createForm(SearchType::class, $dataSearch);
        $form->handleRequest($request);

        [$min, $max] = $productRepo->findMinMaxPrice($dataSearch);
        $products = $productRepo->findSearch($dataSearch);

        return $this->render('/home/home_search.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max,
            'searchData' => $dataSearch
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
}
