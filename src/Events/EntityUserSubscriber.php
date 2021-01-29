<?php

namespace App\Events;

use App\Entity\Product;
use App\Entity\Card;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityUserSubscriber implements EventSubscriberInterface
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForProductCommentCard', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForProductCommentCard(ViewEvent $event)
    {
        $current = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($current instanceof Product && $method === "POST") {
            // Choper l'utilisateur actuellement connecté
            $user = $this->security->getUser();
            // Assigner l'utilisateur au Product qu'on est en train de créer
            if ($user) {
                $current->setUserId($user);
            }
        }else if ($current instanceof Card && $method === "POST") {
            // Choper l'utilisateur actuellement connecté
            $user1 = $this->security->getUser();
            // Assigner l'utilisateur au Card qu'on est en train de créer
            if ($user1) {
                $current->setUserId($user1);
            }
        }else if ($current instanceof Comment && $method === "POST") {
            // Choper l'utilisateur actuellement connecté
            $user2 = $this->security->getUser();
            // Assigner l'utilisateur au Comment qu'on est en train de créer
            if ($user2) {
                $current->setAuthor($user2);
            }
        }else {

        }
    }
}
