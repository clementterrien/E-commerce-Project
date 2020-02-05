<?php

namespace Application\BackendBundle\EventListener;

use App\Entity\User;
use App\Entity\FavoriteList;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


class UserEntityListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $list = new FavoriteList;
            $entity->setFavoriteList($list);
            $entity->setCreatedAt(new \DateTime());
        }
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }
}
