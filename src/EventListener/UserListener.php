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
            dump($entity);
            $entity->setCreatedAt(new \DateTime());
            dd($entity);
            $this->encodePassword($entity);
            $entity->setEnabled(true);
            $entity->setConfirmationToken($token);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $entity = $args->getEntity();
            $this->emailService->sendRegistrationConfirmEmail($entity);
        }
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity)
    {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }
}
