<?php

namespace App\Controller;

use App\Form\UserModificationType;
use App\Repository\UserRepository;
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
    public function showMyInfos(Request $request, UserRepository $userRepo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $dbUser = $userRepo->findOneBy(['id' => 13]);
        $user = $this->getUser();
        $form = $this->createForm(UserModificationType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enteredPassword = $request->request->get('user_modification')['plainPassword'];
            $passwordEncoder = $this->passwordEncoder;
            $manager = $this->getDoctrine()->getManager();

            // dd($user, $dbUser);

            if ($passwordEncoder->isPasswordValid($user, $enteredPassword)) {
                $this->addFlash('success', 'Vos informations ont bien été modifiées');
                $manager->flush();
                return $this->redirectToRoute('account_infos');
            } else {
                dd('It\'s not!!!!');
            }
        }
        return $this->render('account/myaccount-infos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
