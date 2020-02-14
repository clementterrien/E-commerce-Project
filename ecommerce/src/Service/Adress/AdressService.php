<?php

namespace App\Service\Adress;

use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        if (count($this->adressRepo->findBy(['active' => 1, "User" => $this->user])) > 1) {
            throw new Exception("There is more than 1 default adress for this user in the DB");
        }
        return $this->adressRepo->findOneBy(['active' => 1, 'User' => $this->user]);
    }
}
