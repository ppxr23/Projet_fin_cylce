<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Récupère le contenu actuel du token
        $data = $event->getData();

        // Ajoute le nom et prénom
        $data['name'] = $user->getName();
        $data['firstname'] = $user->getFirstname();

        // Réinjecte le nouveau payload
        $event->setData($data);
    }
}
