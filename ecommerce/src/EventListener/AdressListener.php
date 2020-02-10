<?php

namespace Application\BackendBundle\EventListener;

use App\Entity\Adress;
use App\Repository\AdressRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class AdressEntityListener
{
    protected $user;
    protected $adressRepo;

    public function __construct(Security $security, AdressRepository $adressRepo)
    {
        $this->user = $security->getUser();
        $this->adressRepo = $adressRepo;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Adress) {
            $this->user->addAdress($entity);
        }

        if ($this->adressRepo->findBy(['User' => $this->user]) == null) {
            $entity->setActive(true);
        } else {
            $entity->setActive(false);
        }
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }
}
