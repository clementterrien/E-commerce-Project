<?php

namespace App\Controller;

use App\Form\UserModificationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function showMyAccount()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('account/myaccount.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/mes-infos", name="account_infos")
     */
    public function showMyInfos(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Vos informations ont bien été modifiées !');
                return $this->redirectToRoute('account_infos');
            } else {
                $this->addFlash('failure', 'Vous devez confirmer votre mot de passe pour modifier vos informations');
                return $this->redirectToRoute('account_infos');
            }
        }

        return $this->render('account/myaccount-infos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
