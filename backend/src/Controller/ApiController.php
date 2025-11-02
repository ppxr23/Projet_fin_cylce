<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(UserInterface $user, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $token = $jwtManager->create($user);

        return new JsonResponse([
            'token' => $token,
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }  

    #[Route('/api/deconnexion', name: 'api_deconnexion', methods: ['POST'])]
    public function deconnexion(UserInterface $user, EntityManagerInterface $em): JsonResponse
    {
        $newDate = new \DateTime('now', new \DateTimeZone('UTC'));
        $user->setLastConnexion($newDate);

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'message' => 'Déconnexion réussie',
            'last_connexion' => $user->getLastConnexion()->format('Y-m-d H:i:s')
        ], Response::HTTP_OK);
    }

}
