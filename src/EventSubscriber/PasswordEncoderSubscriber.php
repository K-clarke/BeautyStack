<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriber
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * PasswordEncoderSubscriber constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents(): array
    {
        return ['prePersist'];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        /** @var User $entity */
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $encodedPassword = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPass()
        );
        $entity->setPassword($encodedPassword);
    }
}
