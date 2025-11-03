<?php
// src/Controller/ApiController.php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    #[Route('/api/list_users', name: 'api_list_users', methods: ['GET'])]
    public function list_users(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'firstname' => $user->getFirstname(),
                'roles' => $user->getRoles(),
                'status' => $user->isActive(),
                'last_connexion' => $user->getLastConnexion()?->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/delete_user/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé'], Response::HTTP_OK);
    }

}
