<?php

namespace Application\BackendBundle\EventListener;

use App\Entity\User;
use App\Entity\FavoriteList;
use App\Service\Email\EmailService;
use Doctrine\ORM\Event\LifecycleEventArgs;


class UserEntityListener
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $list = new FavoriteList;
            $token = urlencode(hash("sha256", random_bytes(8)));
            $entity->setFavoriteList($list);
            $entity->setCreatedAt(new \DateTime());
            $entity->setEnabled(true);
            $entity->setConfirmationToken($token);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $entity = $args->getEntity();
            $this->emailService->sendRegistrationConfirmEmail($entity);
        }
    }
}
