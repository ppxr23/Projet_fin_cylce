<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Pas connecté'], 401);
        }

        // retourne les infos directement depuis la base
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roles' => $user->getRoles()  // colonne roles
        ]);
    }

    #[Route('/api/hello', name: 'api_hello', methods: ['GET'])]
    public function hello(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'message' => 'Salut ' . ($user ? $user->getName() : 'inconnu') . ' !',
            'roles' => $user ? $user->getRoles() : []
        ]);
    }

    #[Route('/api/protected', name: 'api_protected', methods: ['GET'])]
    public function protected(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Pas connecté'], 401);
        }

        return $this->json([
            'message' => 'Tu as accès à cette zone secrète !',
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }
}
