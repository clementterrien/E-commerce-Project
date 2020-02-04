<?php

namespace Application\BackendBundle\EventListener;

use App\Entity\User;
use App\Entity\FavoriteList;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\BackendBundle\Entity\UserLog;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEntityListener
{
    public function prePersist(LifecycleEventArgs $args, UserPasswordEncoderInterface $encoder)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $hash = $encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($hash);
            $list = new FavoriteList;
            $entity->setFavoriteList($list);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }
}
