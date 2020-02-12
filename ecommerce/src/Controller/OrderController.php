<?php

namespace App\Controller;

use App\Entity\ConfirmedOrder;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(EntityManagerInterface $em, AdressRepository $adressRepo)
    {
        $user = $this->getUser();
        $adress = $adressRepo->findOneBy(['active' => 1]);
        $order = new ConfirmedOrder;
        $order
            ->setCart('hello')
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setAdress($adress)
            ->setTotalPrice(10);

        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
