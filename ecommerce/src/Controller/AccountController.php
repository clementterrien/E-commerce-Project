<?php

namespace App\Controller;

use App\Form\UserModificationType;
use App\Service\Adress\AdressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
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
            $enteredPassword = $request->request->get('user_modification')['plainPassword'];
            $passwordEncoder = $this->passwordEncoder;
            if ($passwordEncoder->isPasswordValid($user, $enteredPassword)) {
                dd('okay');
            }
        }

        return $this->render('account/myaccount-infos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
