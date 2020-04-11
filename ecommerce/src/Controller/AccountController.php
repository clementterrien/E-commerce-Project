<?php

namespace App\Controller;

use App\Form\UserModificationType;
use App\Service\Adress\AdressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="account_home")
     */
    public function showMyAccount(AdressService $adressService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('account/myaccount.html.twig', [
            'defaultAdress' => $adressService->getDefaultAdress()
        ]);
    }

    /**
     * @Route("/mes-infos", name="account_infos")
     */
    public function showMyInfos(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dd($form->isValid());
        }

        return $this->render('account/myaccount-infos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
