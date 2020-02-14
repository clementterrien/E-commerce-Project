<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\CreateAdressType;
use App\Repository\AdressRepository;
use App\Service\Adress\AdressService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function addAdress(Request $request, EntityManagerInterface $em)
    {
        $adress = new Adress;

        $form = $this->createForm(CreateAdressType::class, $adress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($adress);
            $em->flush();

            return $this->redirectToRoute('adress_show');
        }

        return $this->render('adress/createadress.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/setDefaultAdress/{adress_id}", name="adress_setdefault")
     */
    public function setDefaultAdress(int $adress_id, AdressRepository $adressRepo, EntityManagerInterface $em)
    {
        $adressToSetAsDefault = $adressRepo->findOneBy(['id' => $adress_id])->setActive(true);
        $em->flush();

        return $this->redirectToRoute('adress_show');
    }
}
