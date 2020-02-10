<?php

namespace App\Service\Adress;

use App\Entity\Adress;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AdressService
{
    protected $user;
    protected $em;
    protected $adressRepo;

    public function __construct(Security $security, EntityManagerInterface $em, AdressRepository $adressRepo)
    {
        $this->user = $security->getUser();
        $this->em = $em;
        $this->adressRepo = $adressRepo;
    }

    public function getFullAdresses()
    {
        return $this->user->getAdresses();
    }

    public function getDefaultAdress()
    {
        return $this->adressRepo->findOneBy(['active' => 1]);
    }
}
