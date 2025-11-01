<?php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(UserInterface $user, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $token = $jwtManager->create($user);
        var_dump($user->getEmail());

        return new JsonResponse([
            'token' => $token,
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
