<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\CreateAdressType;
use App\Repository\AdressRepository;
use App\Service\Adress\AdressService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdressController extends AbstractController
{
    /**
     * @Route("/mesadresses", name="adress_show")
     */
    public function showMyAdresses(AdressService $service)
    {
        return $this->render('adress/showadresses.html.twig', [
            'activeAdress' => $service->getDefaultAdress(),
            'adresses' => $service->getFullAdresses()
        ]);
    }

    /**
     * @Route("/add/adress", name="adress_add")
     */
    public function addAdress(Request $request)
    {
        $adress = new Adress;
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CreateAdressType::class, $adress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($adress);
            $manager->flush();

            return $this->redirectToRoute('adress_show');
        }

        return $this->render('adress/createadress.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/o", name="hello")
     */
    public function test(AdressRepository $repo)
    {
        $adress = $this->getUser()->getAdresses();
        dd($adress);
    }
}
