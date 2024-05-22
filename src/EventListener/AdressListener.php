<?php

namespace Application\BackendBundle\EventListener;

use App\Entity\Adress;
use Symfony\Component\Mime\Address;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class AdressEntityListener
{
    protected $user;
    protected $adressRepo;
    protected $em;
    private $defaultAdress;

    public function __construct(Security $security, AdressRepository $adressRepo, EntityManagerInterface $em)
    {
        $this->user = $security->getUser();
        $this->adressRepo = $adressRepo;
        $this->em = $em;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Adress) {
            return;
        }

        $entity->setCreatedAt(new \DateTime());
        $entity->setUser($this->user);

        if ($this->adressRepo->findBy(['User' => $this->user]) == []) {
            $entity->setActive(true);
        } elseif ($entity->getActive() == true) {
            $defaultAdress = $this->adressRepo->findOneBy(['active' => true]);
            $defaultAdress->setActive(false);
            $this->em->flush();
        } else {
            $entity->setActive(false);
        }
    }


    public function postPersist(LifecycleEventArgs $args)
    {
    }

    /**
     * Set to false the active attribute of the initial default adress
     * if the active attribute have been changed (set to true) in an adress
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->defaultAdress = $this->adressRepo->findOneBy(['active' => true]);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Adress) {
            return;
        }

        $defaultAdress = $this->defaultAdress;

        if ($entity === $defaultAdress) {
            return;
        } else {
            if ($entity->getActive() == true) {
                $defaultAdress->setActive(false);
                $this->em->flush();
            }
        }
    }

    public function getDefaultAdress()
    {
        return $this->defaultAdress;
    }

    public function setDefaultAdresse(Address $adress): self
    {
        $this->defaultAdress = $adress;

        return $this;
    }
}
